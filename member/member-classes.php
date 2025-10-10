<?php
require_once '../includes/session_check.php';
include '../db.php';
check_session(['member']);

// Check if member is logged in
if (!isset($_SESSION['member_id'])) {
    header('Location: ../login.php');
    exit;
}

$member_id = $_SESSION['member_id'];

// Helper function to convert time slot to actual time
function getTimeForSlot($slot) {
    switch ($slot) {
        case 'Morning':
            return '7:00 AM - 9:00 AM';
        case 'Afternoon':
            return '2:00 PM - 4:00 PM';
        case 'Evening':
            return '6:00 PM - 8:00 PM';
        default:
            return '';
    }
}

// Get selected day (default to today)
$selected_day = isset($_GET['day']) ? $_GET['day'] : date('l');
$current_day = date('l');

// Handle class booking request via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_class'])) {
    $response = ['success' => false, 'msg' => ''];

    $class_id = intval($_POST['class_id']);
    $booking_date = $_POST['booking_date'];

    $conn->begin_transaction();

    try {
        // Check if member has already booked this class for this date
        $check_booking = $conn->prepare("
            SELECT 1 FROM class_bookings 
            WHERE member_id = ? AND class_id = ? AND booking_date = ?
            AND status = 'booked'
        ");
        if (!$check_booking) throw new Exception($conn->error);
        $check_booking->bind_param("iis", $member_id, $class_id, $booking_date);
        $check_booking->execute();
        if ($check_booking->get_result()->num_rows > 0) {
            throw new Exception("You have already booked this class for this date.");
        }

        // Check class capacity
        $check_capacity = $conn->prepare("
            SELECT 
                fc.capacity,
                fc.trainer_id,
                (
                    SELECT COUNT(*) 
                    FROM class_bookings cb 
                    WHERE cb.class_id = fc.id 
                    AND cb.booking_date = ?
                    AND cb.status = 'booked'
                ) as current_bookings
            FROM fitness_classes fc
            WHERE fc.id = ?
        ");
        if (!$check_capacity) throw new Exception($conn->error);
        $check_capacity->bind_param("si", $booking_date, $class_id);
        $check_capacity->execute();
        $details = $check_capacity->get_result()->fetch_assoc();

        if ($details['current_bookings'] >= $details['capacity']) {
            throw new Exception("This class is fully booked for the selected date.");
        }

        // Book the class
        $book_stmt = $conn->prepare("
            INSERT INTO class_bookings 
            (class_id, member_id, booking_date, status) 
            VALUES (?, ?, ?, 'booked')
        ");
        if (!$book_stmt) throw new Exception($conn->error);
        $book_stmt->bind_param("iis", $class_id, $member_id, $booking_date);
        $book_stmt->execute();

        // Create notification for trainer
        $notification_stmt = $conn->prepare("
            INSERT INTO notifications 
            (employee_id, member_id, message, created_at) 
            VALUES (?, ?, CONCAT('New booking for ', (SELECT name FROM fitness_classes WHERE id = ?)), NOW())
        ");
        if (!$notification_stmt) throw new Exception($conn->error);
        $notification_stmt->bind_param("iii", $details['trainer_id'], $member_id, $class_id);
        $notification_stmt->execute();

        $conn->commit();
        $response['success'] = true;
        $response['msg'] = 'Class booked successfully!';
    } catch (Exception $e) {
        $conn->rollback();
        $response['msg'] = $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Fetch classes for the selected day
$classQuery = "
    SELECT 
        fc.id AS class_id,
        fc.name AS class_name,
        fc.description,
        fc.day_of_week,
        fc.time_slot,
        fc.capacity,
        e.id AS trainer_id,
        CONCAT(e.first_name, ' ', e.last_name) as trainer_name,
        e.position,
        (
            SELECT COUNT(*) 
            FROM class_bookings cb
            WHERE cb.class_id = fc.id
            AND cb.booking_date = CURDATE()
            AND cb.status = 'booked'
        ) as current_bookings
    FROM fitness_classes fc
    JOIN employees e ON fc.trainer_id = e.id
    WHERE fc.day_of_week = ?
    AND e.status = 'Active'
    ORDER BY FIELD(fc.time_slot, 'Morning', 'Afternoon', 'Evening')
";

// Prepare the statement safely
$stmt = $conn->prepare($classQuery);
if (!$stmt) die("SQL Prepare Error: " . $conn->error);
$stmt->bind_param("s", $selected_day);
$stmt->execute();
$classResult = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nexus | Member - Classes</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="member.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .day-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .day-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        background: #2d3748;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .day-btn:hover {
        background: #4a5568;
    }

    .day-btn.active {
        background: #e53e3e;
    }

    .class-card {
        background: #2d3748;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .class-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .class-name {
        font-size: 1.25rem;
        font-weight: bold;
        color: white;
    }

    .class-time {
        color: #a0aec0;
    }

    .class-description {
        color: #a0aec0;
        margin-bottom: 15px;
    }

    .class-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .trainer-info {
        color: white;
    }

    .trainer-position {
        color: #a0aec0;
        font-size: 0.9rem;
    }

    .booking-section {
        text-align: right;
    }

    .avail-label {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .avail-yes {
        background: #2f855a;
        color: white;
    }

    .avail-no {
        background: #c53030;
        color: white;
    }

    .btn-book {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        background: #e53e3e;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-book:hover {
        background: #c53030;
    }

    .btn-book:disabled {
        background: #718096;
        cursor: not-allowed;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #2d3748;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
        position: relative;
    }

    .close {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 28px;
        cursor: pointer;
        color: #a0aec0;
    }

    .booking-form {
        margin-top: 20px;
    }

    .booking-form button {
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        background: #e53e3e;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .booking-form button:hover {
        background: #c53030;
    }

    .msg-success {
        background: #2f855a;
        color: white;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        text-align: center;
    }

    #classInfo {
        color: white;
        line-height: 1.6;
    }

    #classInfo div {
        margin-bottom: 8px;
    }

    .modal h2 {
        color: white;
        margin-bottom: 20px;
    }
</style>
</head>
<body>
<div class="sidebar">
    <div class="logo">NEXUS</div>
    <ul class="nav-menu">
        <li><a href="member-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" class="nav-icon"> Dashboard</a></li>
        <li class="active"><a href="member-classes.php"><img src="../images/icons/dashboard-classes-icon.svg" class="nav-icon"> Classes</a></li>
        <li><a href="member-my-plan.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" class="nav-icon"> My Plan</a></li>
        <li><a href="member-progress.php"><img src="../images/icons/dashboard-progress-icon.svg" class="nav-icon"> Progress</a></li>
        <li><a href="member-subscription.php"><img src="../images/icons/dashboard-payment-icon.svg" class="nav-icon"> Subscription</a></li>
        <li><a href="member-profile.php"><img src="../images/icons/dashboard-profile-icon.svg" class="nav-icon"> Profile</a></li>
    </ul>
    <div class="logout-container">
        <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" class="nav-icon"> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h2>Fitness Classes</h2>
        <div class="user-profile">
            <img src="../images/profile pictures/default-profile.svg" alt="User">
            <span>Member</span>
        </div>
    </div>

    <div class="day-selector">
        <?php
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        foreach ($days as $day) {
            $activeClass = ($day === $selected_day) ? 'active' : '';
            echo "<button class='day-btn $activeClass' data-day='$day'>$day</button>";
        }
        ?>
    </div>

    <div class="card">
        <div class="card-content">
            <?php if ($classResult && $classResult->num_rows > 0): ?>
                <?php while ($class = $classResult->fetch_assoc()): ?>
                    <div class="class-card">
                        <div class="class-header">
                            <div class="class-name"><?= htmlspecialchars($class['class_name']) ?></div>
                            <div class="class-time"><?= htmlspecialchars(getTimeForSlot($class['time_slot'])) ?></div>
                        </div>
                        <div class="class-description"><?= htmlspecialchars($class['description']) ?></div>
                        <div class="class-details">
                            <div class="trainer-info">
                                <div><?= htmlspecialchars($class['trainer_name']) ?></div>
                                <div class="trainer-position"><?= htmlspecialchars($class['position']) ?></div>
                            </div>
                            <div class="booking-section">
                                <?php
                                $spots_left = $class['capacity'] - $class['current_bookings'];
                                $availability_class = $spots_left > 0 ? 'avail-yes' : 'avail-no';
                                $availability_text = $spots_left > 0 ? "$spots_left spots left" : "Fully Booked";
                                ?>
                                <div class="avail-label <?= $availability_class ?>" style="margin-bottom: 10px;">
                                    <?= $availability_text ?>
                                </div>
                                <?php if ($spots_left > 0): ?>
                                    <button class="btn-book" onclick="bookClass(<?= $class['class_id'] ?>, '<?= htmlspecialchars($class['class_name']) ?>', '<?= htmlspecialchars($class['time_slot']) ?>', '<?= htmlspecialchars($class['trainer_name']) ?>')">Book Class</button>
                                <?php else: ?>
                                    <button class="btn-book" disabled>Fully Booked</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 20px;">No classes scheduled for <?= htmlspecialchars($selected_day) ?>.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Book a Class</h2>
        <form id="bookingForm" class="booking-form">
            <input type="hidden" name="class_id" id="class_id">
            <input type="hidden" name="book_class" value="1">
            <input type="hidden" name="booking_date" id="booking_date">
            <div id="classInfo" style="margin-bottom: 20px;"></div>
            <button type="submit">Confirm Booking</button>
        </form>
    </div>
</div>

<script>
// Day selection
document.querySelectorAll('.day-btn').forEach(button => {
    button.addEventListener('click', function() {
        const day = this.dataset.day;
        window.location.href = `member-classes.php?day=${day}`;
    });
});

function bookClass(classId, className, timeSlot, trainerName) {
    const today = new Date();
    const selectedDay = '<?= $selected_day ?>';
    const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const todayIndex = today.getDay();
    const selectedDayIndex = days.indexOf(selectedDay);
    let daysToAdd = selectedDayIndex - todayIndex;
    if (daysToAdd <= 0) daysToAdd += 7;
    const bookingDate = new Date(today);
    bookingDate.setDate(today.getDate() + daysToAdd);
    const formattedDate = bookingDate.toISOString().split('T')[0];

    document.getElementById('class_id').value = classId;
    document.getElementById('booking_date').value = formattedDate;
    document.getElementById('classInfo').innerHTML = `
        <div><strong>Class:</strong> ${className}</div>
        <div><strong>Day:</strong> ${selectedDay}</div>
        <div><strong>Time:</strong> ${getTimeForSlot(timeSlot)}</div>
        <div><strong>Date:</strong> ${formatDate(formattedDate)}</div>
        <div><strong>Trainer:</strong> ${trainerName}</div>
    `;
    document.getElementById('bookingModal').style.display = 'block';
}

function getTimeForSlot(slot) {
    switch (slot) {
        case 'Morning': return '7:00 AM - 9:00 AM';
        case 'Afternoon': return '2:00 PM - 4:00 PM';
        case 'Evening': return '6:00 PM - 8:00 PM';
        default: return '';
    }
}
function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
}
document.querySelector('.close').onclick = function() {
    document.getElementById('bookingModal').style.display = 'none';
    document.getElementById('bookingForm').reset();
}
window.onclick = function(event) {
    if(event.target == document.getElementById('bookingModal')) {
        document.getElementById('bookingModal').style.display = 'none';
        document.getElementById('bookingForm').reset();
    }
}

// AJAX submit
$('#bookingForm').on('submit', function(e){
    e.preventDefault();
    const form = $(this);
    const btn = form.find('button');
    btn.prop('disabled', true).text('Booking...');
    $.post(window.location.href, form.serialize(), function(resp){
        if(resp.success){
            $('#bookingModal').hide();
            $('<div class="msg-success">'+resp.msg+'</div>').insertBefore('.card').delay(2500).fadeOut(400,function(){ $(this).remove(); });
            form[0].reset();
            setTimeout(()=>location.reload(),3000);
        }else{
            alert(resp.msg || 'Failed to book class.');
        }
        btn.prop('disabled', false).text('Confirm Booking');
    }, 'json').fail(function(){
        btn.prop('disabled', false).text('Confirm Booking');
        alert('Failed to book class. Please try again.');
    });
});
</script>
</body>
</html>
