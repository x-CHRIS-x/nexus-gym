<?php
include '../db.php';
session_start();

// Logged-in member ID
$member_id = $_SESSION['member_id'] ?? 1;

// Handle AJAX or normal hire request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hire_coach'])) {
    $coach_id = intval($_POST['coach_id']);
    $message = "hired";

    $stmt = $conn->prepare("INSERT INTO notifications (employee_id, member_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $coach_id, $member_id, $message);
    $ok = $stmt->execute();
    $stmt->close();

    if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
        header('Content-Type: application/json');
        echo json_encode(['success' => $ok, 'msg' => $ok ? 'Coach hired successfully!' : 'Failed to hire.']);
        exit;
    }
}

// Fetch coaches with availability
$coachQuery = "
    SELECT e.id AS coach_id, e.full_name, e.position,
           COUNT(ca.id) AS availability_count,
           COALESCE(GROUP_CONCAT(CONCAT(ca.available_day,' ',ca.available_time) SEPARATOR ' | '), '') AS availability_list
    FROM employees e
    LEFT JOIN coach_availability ca ON e.id = ca.employee_id
    WHERE e.status='Active' AND e.full_name LIKE '%Coach%'
    GROUP BY e.id
    ORDER BY e.full_name ASC
";
$coachResult = $conn->query($coachQuery);
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
.msg-success { color: #0a7a00; padding:10px 0; }
.btn-hire { padding:6px 10px; border-radius:6px; border:none; cursor:pointer; background:#1a73e8; color:#fff; }
.btn-hired { background:#6c757d; cursor:default; opacity:.8; }
.avail-label { padding:4px 8px; border-radius:6px; font-weight:600; }
.avail-yes { background:#d8f5d8; color:#0a7a00; }
.avail-no { background:#ffecec; color:#c11; }
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
                                <?php if (intval($c['availability_count'])>0): ?>
                                <div class="avail-label avail-yes"><?= nl2br(htmlspecialchars($c['availability_list'])) ?></div>
                                <?php else: ?>
                                <div class="avail-label avail-no">Not Available</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (intval($c['availability_count'])>0): ?>
                                <form class="hire-form" method="POST" style="display:inline;">
                                    <input type="hidden" name="coach_id" value="<?= $c['coach_id'] ?>">
                                    <input type="hidden" name="hire_coach" value="1">
                                    <input type="hidden" name="ajax" value="1">
                                    <button type="submit" class="btn-hire">Hire Coach</button>
                                </form>
                                <?php else: ?>
                                <button class="btn-hire btn-hired" disabled>Hire</button>
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

<script>
$(function(){
    $('.hire-form').on('submit', function(e){
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button');

        btn.prop('disabled', true).text('Hiring...');

        $.post(window.location.href, form.serialize(), function(resp){
            if (resp && resp.success) {
                btn.text('Hired').addClass('btn-hired');
                $('<div class="msg-success">Coach hired successfully!</div>')
                  .insertBefore('.card').delay(2500).fadeOut(400, function(){ $(this).remove(); });
            } else {
                btn.prop('disabled', false).text('Hire Coach');
                alert(resp.msg || 'Failed to hire coach.');
            }
        }, 'json').fail(function(){
            location.reload();
        });
    });
});
</script>
</body>
</html>
