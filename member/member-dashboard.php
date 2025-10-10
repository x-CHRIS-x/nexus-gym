<?php
session_start();
include '../db.php';
include 'check_membership.php';

// Check if member is logged in and handle expired membership
if (!isset($_SESSION['member_id'])) {
    header('Location: ../login.php');
    exit;
}

handleExpiredMembership($conn, $_SESSION['member_id']);

// Get logged-in member ID
$member_id = $_SESSION['member_id'] ?? null;

// Handle attendance mark
if (isset($_POST['mark_attendance'])) {
    $today = date('Y-m-d');

    // Insert attendance only if not already marked today
    $stmt = $conn->prepare("INSERT IGNORE INTO member_attendance (member_id, session_date) VALUES (?, ?)");
    $stmt->bind_param("is", $member_id, $today);
    $stmt->execute();
    $stmt->close();
    
    header("Location: member-dashboard.php"); // Refresh page to update UI
    exit;
}

// Count total sessions attended
$stmt = $conn->prepare("SELECT COUNT(*) as session_count FROM member_attendance WHERE member_id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$session_count = $stmt->get_result()->fetch_assoc()['session_count'] ?? 0;
$stmt->close();

// Check if already marked today
$stmt = $conn->prepare("SELECT 1 FROM member_attendance WHERE member_id = ? AND session_date = ?");
$today = date('Y-m-d');
$stmt->bind_param("is", $member_id, $today);
$stmt->execute();
$already_attended = $stmt->get_result()->num_rows > 0;
$stmt->close();

// Fetch member details and progress if logged in
$progress = null;
$subscription = null;
$member = null;
if ($member_id) {
    // Fetch subscription details
    $sub_stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
    $sub_stmt->bind_param("i", $member_id);
    $sub_stmt->execute();
    $member = $sub_stmt->get_result()->fetch_assoc();
    $sub_stmt->close();
    
    $subscription = $member;
    $todayDate = new DateTime();
    $end_date = new DateTime($subscription['membership_end_date']);
    $subscription['subscription_status'] = ($end_date > $todayDate) ? 'Active' : 'Expired';
    
    $stmt = $conn->prepare("SELECT * FROM member_progress WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $progress = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nexus | Member Dashboard</title>
<link rel="stylesheet" href="member.css">
<style>
/* Progress box styling - dark theme */
.progress-columns {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-top: 15px;
}
.progress-box {
    flex: 1;
    background: #1e2a38;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0px 3px 6px rgba(0,0,0,0.4);
    color: #f5f5f5;
}
.progress-box h3 {
    font-size: 16px;
    margin-bottom: 10px;
    text-align: center;
    color: #ffffff;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    padding-bottom: 5px;
}
.progress-item {
    margin: 6px 0;
    font-size: 14px;
    padding: 8px;
    background: rgba(255,255,255,0.05);
    border-radius: 6px;
    display: flex;
    justify-content: space-between;
}
.progress-label {
    font-weight: bold;
    color: #ddd;
}
.progress-value {
    color: #00c4ff;
}
.action-btn {
    background-color: #00c4ff;
    color: #fff;
    padding: 8px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
.action-btn:hover {
    background-color: #0093cc;
}

/* Member Info Card */
.members-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 15px;
}
.member-card {
    border:1px solid #ddd;
    border-radius:8px;
    padding:15px;
    width:250px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
    background:#1e2a38;
    color:#f5f5f5;
}
.member-card h3 {
    margin:0 0 10px 0;
    font-size:1.2em;
    border-bottom:1px solid rgba(255,255,255,0.1);
    padding-bottom:5px;
    text-align:center;
}
.member-card p {
    margin:5px 0;
}
</style>
</head>
<body>
<!-- Sidebar (always open) -->
<div class="sidebar" id="sidebar">
    <div class="logo">NEXUS</div>
    <ul class="nav-menu">
        <li class="active"><a href="member-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
        <li><a href="member-classes.php"><img src="../images/icons/dashboard-classes-icon.svg" alt="Classes" class="nav-icon"> Classes</a></li>
        <li><a href="member-my-plan.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" alt="My Plan" class="nav-icon"> My Plan</a></li>
        <li><a href="member-progress.php"><img src="../images/icons/dashboard-progress-icon.svg" alt="Progress" class="nav-icon"> Progress</a></li>
        <li><a href="member-subscription.php"><img src="../images/icons/dashboard-payment-icon.svg" alt="Subscription" class="nav-icon"> Subscription</a></li>
        <li><a href="member-profile.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Profile" class="nav-icon"> Profile</a></li>
    </ul>
    <div class="logout-container">
        <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
<div class="header">
    <h2>Member Dashboard</h2>
    <div class="dashboard-datetime dashboard-datetime-style" id="dashboard-datetime-member"></div>
    <div class="user-profile">
        <img src="../images/profile pictures/default-profile.svg" alt="User">
        <span>Member</span>
    </div>
    <script>
    function updateDateTimeMember() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString(undefined, options);
        const timeStr = now.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('dashboard-datetime-member').textContent = `${dateStr} | ${timeStr}`;
    }
    setInterval(updateDateTimeMember, 1000);
    updateDateTimeMember();
    </script>
</div>

<!-- Top summary cards row -->
<div class="dashboard-summary-row">
    <div class="dashboard-card">
        <img src="../images/icons/dashboard-My_Plan-icon.svg" class="summary-icon" alt="Membership Status">
        <div class="summary-number subscription-<?= strtolower($subscription['subscription_status']) ?>"><?= htmlspecialchars(ucfirst($subscription['subscription_status'])) ?></div>
        <div class="summary-label">Membership Status</div>
    </div>

    <div class="dashboard-card">
        <img src="../images/icons/dashboard-classes-icon.svg" class="summary-icon" alt="Attendance">
        <div class="summary-number"><?= $session_count ?></div>
        <div class="summary-label">Total Sessions</div>
        <form method="post" style="margin-top:8px; text-align:center;">
            <button type="submit" name="mark_attendance" class="action-btn"
                <?= $already_attended ? 'disabled style="background:gray; cursor:not-allowed;"' : '' ?>>
                <?= $already_attended ? 'Marked Today' : 'Mark Attendance' ?>
            </button>
        </form>
    </div>

    <div class="dashboard-card">
    <img src="../images/icons/dashboard-profile-icon.svg" class="summary-icon" alt="Welcome">
    <div class="summary-number">Welcome <?= htmlspecialchars($member['first_name'] ?? 'Member') ?></div>
    <div class="summary-label" style="text-align:center; margin-top:5px;">
        <button class="action-btn" onclick="window.location.href='member-profile.php'" style="padding:6px 12px; font-size:14px;">
            See Profile
        </button>
    </div>
</div>


    <div class="dashboard-card">
        <img src="../images/icons/dashboard-payment-icon.svg" class="summary-icon" alt="Subscription Status">
        <div class="summary-number subscription-expiry-<?= strtolower($subscription['subscription_status']) ?>">Expires <?= date('M d, Y', strtotime($subscription['membership_end_date'])) ?></div>
        <div class="summary-label">Subscription Status</div>
    </div>
</div>

<!-- Your Progress -->
<div class="card card-margin-bottom">
    <div class="employee-table-title">Your Progress</div>
    <div class="progress-columns">
        <div class="progress-box">
            <h3>Chest / Shoulders</h3>
            <div class="progress-item"><span class="progress-label">Bench Press</span> <span class="progress-value"><?= $progress['bench_press'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Incline Press</span> <span class="progress-value"><?= $progress['incline_press'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Decline Press</span> <span class="progress-value"><?= $progress['decline_press'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Chest Fly</span> <span class="progress-value"><?= $progress['chest_fly'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Overhead Press</span> <span class="progress-value"><?= $progress['overhead_press'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Lateral Raises</span> <span class="progress-value"><?= $progress['lateral_raises'] ?? '-' ?></span></div>
        </div>

        <div class="progress-box">
            <h3>Back / Biceps</h3>
            <div class="progress-item"><span class="progress-label">Deadlift</span> <span class="progress-value"><?= $progress['deadlift'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Lat Pulldown</span> <span class="progress-value"><?= $progress['lat_pulldown'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Weight Now (kg)</span> <span class="progress-value"><?= $progress['weight_now'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Weight Last Month (kg)</span> <span class="progress-value"><?= $progress['weight_before'] ?? '-' ?></span></div>
        </div>

        <div class="progress-box">
            <h3>Legs</h3>
            <div class="progress-item"><span class="progress-label">Squat</span> <span class="progress-value"><?= $progress['squat'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Leg Press</span> <span class="progress-value"><?= $progress['leg_press'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">Romanian Deadlift</span> <span class="progress-value"><?= $progress['romanian_deadlift'] ?? '-' ?></span></div>
            <div class="progress-item"><span class="progress-label">RDL</span> <span class="progress-value"><?= $progress['rdl'] ?? '-' ?></span></div>
        </div>
    </div>

    <div style="margin-top: 15px; text-align: center;">
        <button class="action-btn edit-btn" onclick="window.location.href='member-progress.php'">
            View Past Record
        </button>
    </div>
</div>

<!-- ===== NEW MEMBER INFO CARD ===== -->
<div class="card card-margin-bottom">
    <div class="employee-table-title">Member Info</div>
    <div class="members-container">
        <?php
        $full_name = htmlspecialchars($member['first_name'] ?? 'Member') . ' ' . htmlspecialchars($member['last_name'] ?? '');
        ?>
        <div class="member-card">
            <h3><?= $full_name ?></h3>
            <p><strong>Email:</strong> <?= htmlspecialchars($member['email'] ?? '-') ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($member['phone'] ?? '-') ?></p>
            <p><strong>Membership:</strong> <?= htmlspecialchars($member['membership_type'] ?? '-') ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($subscription['subscription_status'] ?? '-') ?></p>
        </div>
    </div>
</div>

<!-- Available Coaches View -->
<div class="card card-margin-bottom">
    <div class="employee-table-title">Available Coaches</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Coach Name</th>
                    <th>Position</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $coachQuery = "
                    SELECT e.id, CONCAT(e.first_name, ' ', e.last_name) AS full_name, e.position, 
                    CASE WHEN EXISTS (SELECT 1 FROM coach_availability ca WHERE ca.employee_id = e.id) THEN 1 ELSE 0 END AS is_available
                    FROM employees e
                    WHERE e.status = 'Active' AND (e.position LIKE '%Coach%' OR e.position LIKE '%Trainer%')
                    ORDER BY e.first_name, e.last_name
                ";
                $coachResult = $conn->query($coachQuery);
                if ($coachResult && $coachResult->num_rows > 0):
                    while($coach = $coachResult->fetch_assoc()):
                ?>
                        <tr>
                            <td><?= htmlspecialchars($coach['full_name']); ?></td>
                            <td><?= htmlspecialchars($coach['position']); ?></td>
                            <td>
                                <span style="padding:4px 8px; border-radius:4px; color:#fff; font-weight:600; 
                                    background:<?= $coach['is_available'] ? 'green' : 'red'; ?>;">
                                    <?= $coach['is_available'] ? 'Available' : 'Unavailable'; ?>
                                </span>
                            </td>
                        </tr>
                <?php
                    endwhile;
                else:
                    echo '<tr><td colspan="3" style="text-align:center;">No coaches available</td></tr>';
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Subscription Reminder -->
<div class="card">
    <div class="employee-table-title">Subscription Reminder</div>
    <div class="subscription-reminder-row">
        <span>Expiry date: <strong><?= date('M d, Y', strtotime($subscription['membership_end_date'])) ?></strong></span>
        <?php if (strtotime($subscription['membership_end_date']) <= strtotime('+30 days')): ?>
            <button class="action-btn edit-btn" onclick="window.location.href='member-subscription.php'">Renew</button>
        <?php endif; ?>
    </div>
</div>

</div>
</body>
</html>
