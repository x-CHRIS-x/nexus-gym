<?php
include '../db.php';
session_start();

// Logged-in member ID
$member_id = $_SESSION['member_id'] ?? 1;

// Handle booking request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_session'])) {
    $trainer_id = intval($_POST['trainer_id']);
    $session_date = $_POST['session_date'];
    $session_time = $_POST['session_time'];

    // Start transaction
    $conn->begin_transaction();
    try {
        // Check if trainer is already hired, if not, create hire notification
        $check_hired = $conn->prepare("SELECT 1 FROM notifications WHERE employee_id = ? AND member_id = ? AND message = 'hired'");
        $check_hired->bind_param("ii", $trainer_id, $member_id);
        $check_hired->execute();
        $is_hired = $check_hired->get_result()->num_rows > 0;
        $check_hired->close();

        if (!$is_hired) {
            $hire_stmt = $conn->prepare("INSERT INTO notifications (employee_id, member_id, message) VALUES (?, ?, 'hired')");
            $hire_stmt->bind_param("ii", $trainer_id, $member_id);
            $hire_stmt->execute();
            $hire_stmt->close();
        }

        // Book the session
        $book_stmt = $conn->prepare("INSERT INTO training_sessions (member_id, trainer_id, session_date, session_time) VALUES (?, ?, ?, ?)");
        $book_stmt->bind_param("iiss", $member_id, $trainer_id, $session_date, $session_time);
        $book_stmt->execute();
        $book_stmt->close();

        $conn->commit();
        $ok = true;
    } catch (Exception $e) {
        $conn->rollback();
        $ok = false;
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => $ok, 'msg' => $ok ? 'Session booked successfully!' : 'Failed to book session']);
    exit;
}

// Fetch coaches with availability
$coachQuery = "
    SELECT 
        e.id AS coach_id, 
        e.full_name, 
        e.position,
        COUNT(ca.id) AS availability_count,
        GROUP_CONCAT(DISTINCT ca.available_day) as available_days,
        GROUP_CONCAT(DISTINCT ca.available_time) as available_times
    FROM employees e
    LEFT JOIN coach_availability ca ON e.id = ca.employee_id
    WHERE e.status='Active' AND e.full_name LIKE '%Coach%'
    GROUP BY e.id
    ORDER BY e.full_name ASC
";
$stmt = $conn->prepare($coachQuery);
    $stmt->execute();
    $coachResult = $stmt->get_result();
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nexus | Member - Classes</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="member.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
.msg-success { color: #0a7a00; padding:10px 0; }
.btn-hire { padding:6px 10px; border-radius:6px; border:none; cursor:pointer; background:#1a73e8; color:#fff; }
.btn-hired { background:#6c757d; cursor:default; opacity:.8; }
.avail-label { padding:4px 8px; border-radius:6px; font-weight:600; }
.avail-yes { background:#d8f5d8; color:#0a7a00; }
.avail-no { background:#ffecec; color:#c11; }

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
}

.modal-content {
    background-color: #1e2a38;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #2d3748;
    width: 80%;
    max-width: 500px;
    border-radius: 8px;
    color: #f5f5f5;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.close {
    color: #f5f5f5;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #00c4ff;
}

.booking-form {
    margin-top: 20px;
}

.booking-form label {
    display: block;
    margin-bottom: 5px;
    color: #f5f5f5;
}

.booking-form input {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    background-color: #2d3748;
    border: 1px solid #4a5568;
    border-radius: 4px;
    color: #f5f5f5;
}

.booking-form input:focus {
    border-color: #00c4ff;
    outline: none;
}

.booking-form button {
    background-color: #00c4ff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

.booking-form button:hover {
    background-color: #0099ff;
}

/* Calendar dark theme overrides */
.flatpickr-calendar {
    background: #1e2a38;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    border: 1px solid #2d3748;
}

.flatpickr-month {
    background: #1e2a38;
    color: #f5f5f5;
}

.flatpickr-weekday {
    background: #1e2a38;
    color: #00c4ff;
}

.flatpickr-day {
    color: #f5f5f5;
    background: #2d3748;
    border: 1px solid #1e2a38;
}

.flatpickr-day.selected {
    background: #00c4ff;
    border-color: #00c4ff;
}

.flatpickr-day:hover {
    background: #4a5568;
}

.flatpickr-day.disabled {
    color: #4a5568;
    background: #1e2a38;
}

.flatpickr-current-month {
    color: #f5f5f5;
}

.flatpickr-time {
    background: #1e2a38;
    border-top: 1px solid #2d3748;
}

.flatpickr-time input {
    color: #f5f5f5;
    background: #2d3748;
}

.flatpickr-time .flatpickr-am-pm {
    color: #f5f5f5;
    background: #2d3748;
}

.numInputWrapper:hover {
    background: #4a5568;
}

.btn-book {
    padding: 8px 15px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    background: #00c4ff;
    color: #fff;
    font-weight: 500;
    transition: background-color 0.2s;
}

.btn-book:hover {
    background: #0099ff;
}

.btn-book:active {
    transform: translateY(1px);
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

    <!-- Trainers List -->
    <div class="card card-margin-top">
        <div class="employee-table-title">Available Trainers & Schedule</div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Trainer</th>
                        <th>Specialization</th>
                        <th>Available Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($coachResult && $coachResult->num_rows > 0): ?>
                        <?php while ($c = $coachResult->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['full_name']) ?></td>
                            <td><?= htmlspecialchars($c['position']) ?></td>
                            <td>
                                <?php if (intval($c['availability_count']) > 0): 
                                    $days = explode(',', $c['available_days']);
                                    $times = explode(',', $c['available_times']);
                                    $formatted_times = array_map(function($time) {
                                        return date('h:i A', strtotime($time));
                                    }, $times);
                                    ?>
                                    <div class="avail-label avail-yes" data-days="<?= htmlspecialchars($c['available_days']) ?>" data-times="<?= htmlspecialchars($c['available_times']) ?>">
                                        <div><strong>Days:</strong> <?= htmlspecialchars(implode(', ', $days)) ?></div>
                                        <div><strong>Times:</strong> <?= htmlspecialchars(implode(', ', $formatted_times)) ?></div>
                                    </div>
                                <?php else: ?>
                                    <div class="avail-label avail-no">Not Available</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (intval($c['availability_count']) > 0): ?>
                                    <button class="btn-book" onclick="openBookingModal(<?= $c['coach_id'] ?>, '<?= htmlspecialchars($c['full_name']) ?>', '<?= htmlspecialchars($c['available_days']) ?>', '<?= htmlspecialchars($c['available_times']) ?>')">Book Session</button>
                                <?php else: ?>
                                    <button class="btn-book" disabled style="opacity: 0.6;">Not Available</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No available coaches at the moment.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Book a Session</h2>
        <form id="bookingForm" class="booking-form">
            <input type="hidden" name="trainer_id" id="trainer_id">
            <input type="hidden" name="book_session" value="1">
            <input type="hidden" name="ajax" value="1">
            
            <div id="trainerInfo"></div>
            <div id="availabilityInfo" style="margin: 10px 0;"></div>
            
            <label for="session_date">Select Date:</label>
            <input type="text" id="session_date" name="session_date" required>
            
            <label for="session_time">Select Time:</label>
            <input type="text" id="session_time" name="session_time" required>
            
            <button type="submit">Book Session</button>
        </form>
    </div>
</div>

<script>
function formatTime(timeStr) {
    // Convert 24-hour time to Date object
    const [hours, minutes] = timeStr.split(':');
    const date = new Date();
    date.setHours(parseInt(hours));
    date.setMinutes(parseInt(minutes));
    return date;
}

function openBookingModal(trainerId, trainerName, availableDays, availableTimes) {
    document.getElementById('trainer_id').value = trainerId;
    document.getElementById('trainerInfo').innerHTML = `<strong>Trainer:</strong> ${trainerName}`;
    
    // Parse available days and times
    const days = availableDays.split(',').map(day => day.trim());
    const times = availableTimes.split(',').map(time => time.trim());
    
    // Convert times to 12-hour format for display
    const formattedTimes = times.map(time => {
        const [hours, minutes] = time.split(':');
        return new Date(2025, 0, 1, hours, minutes).toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    });

    // Show available slots in the modal
    document.getElementById('availabilityInfo').innerHTML = `
        <div style="margin-bottom: 10px;">
            <strong>Available Days:</strong> ${days.join(', ')}
        </div>
        <div>
            <strong>Available Time Slots:</strong> ${formattedTimes.join(', ')}
        </div>
    `;

    // Initialize date picker
    flatpickr('#session_date', {
        dateFormat: 'Y-m-d',
        minDate: 'today',
        maxDate: new Date().fp_incr(60),
        enable: [
            function(date) {
                const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
                return days.includes(dayName);
            }
        ],
        theme: 'dark',
        disableMobile: true
    });

    // Remove any existing time select
    const existingSelect = document.getElementById('time_select');
    if (existingSelect) {
        existingSelect.remove();
    }

    // Create custom time select dropdown
    const timeSelect = document.createElement('select');
    timeSelect.id = 'time_select';
    timeSelect.style.width = '100%';
    timeSelect.style.padding = '8px';
    timeSelect.style.marginBottom = '15px';
    timeSelect.style.backgroundColor = '#2d3748';
    timeSelect.style.border = '1px solid #4a5568';
    timeSelect.style.borderRadius = '4px';
    timeSelect.style.color = '#f5f5f5';
    timeSelect.style.cursor = 'pointer';

    // Add default option
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Select a time slot';
    defaultOption.disabled = true;
    defaultOption.selected = true;
    timeSelect.appendChild(defaultOption);

    // Add available time options
    times.forEach(time => {
        const option = document.createElement('option');
        option.value = time;
        const [hours, minutes] = time.split(':');
        const displayTime = new Date(2025, 0, 1, hours, minutes).toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
        option.textContent = displayTime;
        timeSelect.appendChild(option);
    });

    // Replace the flatpickr time input with our custom select
    const timeInput = document.getElementById('session_time');
    timeInput.type = 'hidden';
    timeInput.parentNode.insertBefore(timeSelect, timeInput);

    // Update hidden input when select changes
    timeSelect.addEventListener('change', function(e) {
        timeInput.value = e.target.value;
    });

    document.getElementById('bookingModal').style.display = 'block';
}

// Close modal when clicking (x) or outside
document.querySelector('.close').onclick = function() {
    document.getElementById('bookingModal').style.display = 'none';
    // Reset form when closing
    document.getElementById('bookingForm').reset();
    const existingSelect = document.getElementById('time_select');
    if (existingSelect) {
        existingSelect.remove();
    }
}

window.onclick = function(event) {
    if (event.target == document.getElementById('bookingModal')) {
        document.getElementById('bookingModal').style.display = 'none';
        // Reset form when closing
        document.getElementById('bookingForm').reset();
        const existingSelect = document.getElementById('time_select');
        if (existingSelect) {
            existingSelect.remove();
        }
    }
}

// Display modal when clicking Book Session
document.addEventListener('DOMContentLoaded', function() {
});

$('#bookingForm').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    const btn = form.find('button');

    btn.prop('disabled', true).text('Booking...');

    $.post(window.location.href, form.serialize(), function(resp) {
        if (resp && resp.success) {
            $('#bookingModal').hide();
            $('<div class="msg-success">Session booked successfully!</div>')
              .insertBefore('.card').delay(2500).fadeOut(400, function(){ $(this).remove(); });
            form[0].reset();
        } else {
            alert(resp.msg || 'Failed to book session.');
        }
        btn.prop('disabled', false).text('Book Session');
    }, 'json').fail(function() {
        btn.prop('disabled', false).text('Book Session');
        alert('Failed to book session. Please try again.');
    });
});
</script>
</body>
</html>
