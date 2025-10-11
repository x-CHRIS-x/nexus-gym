<?php
session_start();
include '../db.php';
require_once '../includes/session_check.php';
check_session(['member']);


$member_id = isset($_SESSION['member_id']) ? (int)$_SESSION['member_id'] : (isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0);
$member_name = $_SESSION['member_name'] ?? $_SESSION['member_full_name'] ?? $_SESSION['full_name'] ?? 'Member';

if ($member_id <= 0 || ($_SESSION['role'] ?? '') !== 'member') {
    header("Location: ../login.php");
    exit();
}

// ------------------------
// Attendance
// ------------------------
$ensureAttendanceSQL = "CREATE TABLE IF NOT EXISTS `member_attendance` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `member_id` INT NOT NULL,
    `session_date` DATE NOT NULL DEFAULT CURRENT_DATE,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($ensureAttendanceSQL);

if (isset($_POST['mark_attendance'])) {
    $today = date('Y-m-d');
    $checkSQL = "SELECT id FROM member_attendance WHERE member_id=? AND session_date=?";
    $stmt = $conn->prepare($checkSQL);
    $stmt->bind_param("is", $member_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $insertSQL = "INSERT INTO member_attendance (member_id, session_date) VALUES (?, ?)";
        $stmt2 = $conn->prepare($insertSQL);
        $stmt2->bind_param("is", $member_id, $today);
        $stmt2->execute();
        $stmt2->close();
        $attendance_msg = "Attendance marked for today!";
    } else {
        $attendance_msg = "You already marked attendance today.";
    }
    $stmt->close();
}

$attendanceSQL = "SELECT * FROM member_attendance WHERE member_id=? ORDER BY session_date DESC";
$stmt = $conn->prepare($attendanceSQL);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$attendance_result = $stmt->get_result();
$stmt->close();

// ------------------------
// Progress
// ------------------------
$ensureSQL = "CREATE TABLE IF NOT EXISTS `member_progress` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `member_id` INT NOT NULL,
    `bench_press` VARCHAR(50) DEFAULT NULL,
    `incline_press` VARCHAR(50) DEFAULT NULL,
    `decline_press` VARCHAR(50) DEFAULT NULL,
    `chest_fly` VARCHAR(50) DEFAULT NULL,
    `overhead_press` VARCHAR(50) DEFAULT NULL,
    `lateral_raises` VARCHAR(50) DEFAULT NULL,
    `deadlift` DECIMAL(6,2) DEFAULT 0.00,
    `lat_pulldown` DECIMAL(6,2) DEFAULT 0.00,
    `weight_now` DECIMAL(6,2) DEFAULT 0.00,
    `weight_before` DECIMAL(6,2) DEFAULT 0.00,
    `squat` VARCHAR(50) DEFAULT NULL,
    `leg_press` VARCHAR(50) DEFAULT NULL,
    `romanian_deadlift` VARCHAR(50) DEFAULT NULL,
    `rdl` VARCHAR(50) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($ensureSQL);

$saved = false;
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['mark_attendance'])) {
    $fields = [
        'bench_press','incline_press','decline_press','chest_fly','overhead_press','lateral_raises',
        'deadlift','lat_pulldown','weight_now','weight_before','squat','leg_press','romanian_deadlift','rdl'
    ];
    $values = [];
    foreach ($fields as $f) {
        $values[$f] = $_POST[$f] ?? null;
    }

    $placeholders = implode(',', array_fill(0, count($fields)+1, '?'));
    $types = 'i' . str_repeat('s',6) . str_repeat('d',4) . str_repeat('s',4);

    $insertSQL = "INSERT INTO member_progress 
        (member_id,".implode(',', $fields).")
        VALUES ($placeholders)";
    $stmt = $conn->prepare($insertSQL);
    if ($stmt) {
        $stmt->bind_param(
            $types,
            $member_id,
            $values['bench_press'], $values['incline_press'], $values['decline_press'], $values['chest_fly'], $values['overhead_press'], $values['lateral_raises'],
            $values['deadlift'], $values['lat_pulldown'], $values['weight_now'], $values['weight_before'],
            $values['squat'], $values['leg_press'], $values['romanian_deadlift'], $values['rdl']
        );
        if (!$stmt->execute()) $err = "DB error: " . $stmt->error;
        else $saved = true;
        $stmt->close();
    } else {
        $err = "DB prepare error: " . $conn->error;
    }
}

$latestSQL = "SELECT * FROM member_progress WHERE member_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($latestSQL);
if ($stmt) {
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $progress = $result->fetch_assoc() ?: null;
    $stmt->close();
} else {
    $progress = null;
}

$historySQL = "SELECT * FROM member_progress WHERE member_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($historySQL);
if ($stmt) {
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $history_result = $stmt->get_result();
    $stmt->close();
} else {
    $history_result = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Nexus | Member - Progress</title>
<link rel="stylesheet" href="member.css">
<style>
.card.card-margin-bottom { margin-bottom: 20px; }
.stats-container { display:flex; gap:18px; flex-wrap:wrap; }
.stat-column {
  flex:1; min-width:260px;
  background:#1f2430; color:#e8eef8;
  border-radius:10px; padding:18px;
  box-shadow:0 6px 18px rgba(0,0,0,0.35);
  border:1px solid rgba(255,255,255,0.03);
}
.stat-column h3 { color:#ffd27a; margin:0 0 12px; font-size:16px; text-align:center; }
.stat-column h4 { color:#9fd08f; margin:12px 0 8px; font-size:14px; text-align:center; }
.stat-column label { display:block; margin:8px 0 4px; font-weight:600; color:#cfe3ff; font-size:13px; }
.stat-column input {
  width:100%; padding:9px; border-radius:8px; border:1px solid rgba(255,255,255,0.06);
  background:#252a36; color:#fff; box-sizing:border-box;
}
.action-btn.edit-btn {
  background: linear-gradient(90deg,#ff7a00,#ff3b00); color:#fff; border:none; padding:10px 20px; border-radius:8px;
  cursor:pointer; font-weight:700;
}
.save-note { margin-top:10px; color:#9fd08f; font-weight:600; }
.error-note { margin-top:10px; color:#ff9b9b; font-weight:600; }
.header h2 { margin:0; }
.history-table { width:100%; border-collapse:collapse; margin-top:20px; }
.history-table th, .history-table td { border:1px solid #444; padding:6px 10px; text-align:center; font-size:13px; color:#ddd; }
.history-table th { background:#2c3440; color:#ffd27a; }
</style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="logo">NEXUS</div>
    <ul class="nav-menu">
        <li><a href="member-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
        <li><a href="member-classes.php"><img src="../images/icons/dashboard-classes-icon.svg" alt="Classes" class="nav-icon"> Classes</a></li>
        <li><a href="member-my-plan.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" alt="My Plan" class="nav-icon"> My Plan</a></li>
        <li class="active"><a href="member-progress.php"><img src="../images/icons/dashboard-progress-icon.svg" alt="Progress" class="nav-icon"> Progress</a></li>
        <li><a href="member-subscription.php"><img src="../images/icons/dashboard-payment-icon.svg" alt="Subscription" class="nav-icon"> Subscription</a></li>
        <li><a href="member-profile.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Profile" class="nav-icon"> Profile</a></li>
    </ul>
    <div class="logout-container">
        <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
    </div>
</div>

<!-- Main content -->
<div class="main-content">
  <div class="header">
    <h2><?php echo htmlspecialchars($member_name); ?> Statistics</h2>
  </div>

  <!-- Attendance Card -->
  <div class="card card-margin-bottom">
    <div class="employee-table-title">Mark Today's Attendance</div>
    <?php if(isset($attendance_msg)): ?>
        <div class="save-note"><?php echo htmlspecialchars($attendance_msg); ?></div>
    <?php endif; ?>
    <form method="post" style="text-align:center; padding:12px;">
        <button type="submit" name="mark_attendance" class="action-btn edit-btn">
            Mark Attendance
        </button>
    </form>
  </div>

  <!-- Attendance History -->
  <div class="card card-margin-bottom">
    <div class="employee-table-title">Attendance History</div>
    <table class="history-table">
        <thead>
            <tr><th>Date Attended</th></tr>
        </thead>
        <tbody>
            <?php if($attendance_result && $attendance_result->num_rows > 0): ?>
                <?php while($row = $attendance_result->fetch_assoc()): ?>
                    <tr><td><?php echo htmlspecialchars($row['session_date']); ?></td></tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td style="text-align:center;">No attendance records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
  </div>

  <?php if ($saved): ?>
    <div class="card card-margin-bottom"><div class="save-note">Progress saved successfully.</div></div>
  <?php elseif ($err): ?>
    <div class="card card-margin-bottom"><div class="error-note"><?php echo htmlspecialchars($err); ?></div></div>
  <?php endif; ?>

  <!-- Progress Form -->
  <form method="post" class="card card-margin-bottom" style="padding:16px;">
    <div class="stats-container">
      <!-- Chest / Shoulders -->
      <div class="stat-column">
        <h3>Chest / Shoulders</h3>
        <?php foreach(['bench_press'=>'Bench Press','incline_press'=>'Incline Bench Press','decline_press'=>'Decline Bench Press','chest_fly'=>'Chest Fly','overhead_press'=>'Overhead Press','lateral_raises'=>'Lateral Raises'] as $field=>$label): ?>
            <label><?php echo $label; ?></label>
            <input name="<?php echo $field; ?>" value="<?php echo htmlspecialchars($progress[$field] ?? ''); ?>">
        <?php endforeach; ?>
      </div>

      <!-- Back / Biceps -->
      <div class="stat-column">
        <h3>Back / Biceps</h3>
        <?php foreach(['deadlift'=>'Deadlift','lat_pulldown'=>'Lat Pulldown','weight_now'=>'Weight Now (kg)','weight_before'=>'Weight Last Month (kg)'] as $field=>$label): ?>
            <label><?php echo $label; ?></label>
            <input name="<?php echo $field; ?>" value="<?php echo htmlspecialchars($progress[$field] ?? ''); ?>">
        <?php endforeach; ?>
      </div>

      <!-- Legs -->
      <div class="stat-column">
        <h3>Legs</h3>
        <?php foreach(['squat'=>'Squat','leg_press'=>'Leg Press','romanian_deadlift'=>'Romanian Deadlift','rdl'=>'RDL'] as $field=>$label): ?>
            <label><?php echo $label; ?></label>
            <input name="<?php echo $field; ?>" value="<?php echo htmlspecialchars($progress[$field] ?? ''); ?>">
        <?php endforeach; ?>
      </div>
    </div>
    <div style="margin-top:14px;">
      <button type="submit" class="action-btn edit-btn">Save Progress</button>
    </div>
  </form>

  <!-- Progress History Table -->
  <div class="card card-margin-bottom">
    <div class="employee-table-title">Past Progress Records</div>
    <table class="history-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Bench Press</th>
                <th>Incline Press</th>
                <th>Decline Press</th>
                <th>Chest Fly</th>
                <th>Overhead Press</th>
                <th>Lateral Raises</th>
                <th>Deadlift</th>
                <th>Lat Pulldown</th>
                <th>Weight Now</th>
                <th>Weight Before</th>
                <th>Squat</th>
                <th>Leg Press</th>
                <th>Romanian Deadlift</th>
                <th>RDL</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($history_result && $history_result->num_rows > 0): ?>
                <?php while($row = $history_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('M d, Y H:i', strtotime($row['created_at'])); ?></td>
                    <?php foreach(['bench_press','incline_press','decline_press','chest_fly','overhead_press','lateral_raises','deadlift','lat_pulldown','weight_now','weight_before','squat','leg_press','romanian_deadlift','rdl'] as $field): ?>
                        <td><?php echo htmlspecialchars($row[$field]); ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="15" style="text-align:center;">No past records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
  </div>

</div>
</body>
</html>
