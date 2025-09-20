<?php
// show errors while developing
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../db.php';

// fallback for session key differences
$member_id = isset($_SESSION['member_id']) ? (int)$_SESSION['member_id'] : (isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0);
$member_name = $_SESSION['member_name'] ?? $_SESSION['member_full_name'] ?? $_SESSION['full_name'] ?? 'Member';

if ($member_id <= 0 || ($_SESSION['role'] ?? '') !== 'member') {
    header("Location: ../login.php");
    exit();
}

// ensure table exists (attempt to create if missing)
$ensureSQL = "CREATE TABLE IF NOT EXISTS `member_progress` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `member_id` INT NOT NULL,
    `bench_press` VARCHAR(50) DEFAULT '',
    `incline_press` VARCHAR(50) DEFAULT '',
    `decline_press` VARCHAR(50) DEFAULT '',
    `chest_fly` VARCHAR(50) DEFAULT '',
    `overhead_press` VARCHAR(50) DEFAULT '',
    `lateral_raises` VARCHAR(50) DEFAULT '',
    `deadlift` VARCHAR(50) DEFAULT '',
    `lat_pulldown` VARCHAR(50) DEFAULT '',
    `weight_now` VARCHAR(50) DEFAULT '',
    `weight_before` VARCHAR(50) DEFAULT '',
    `squat` VARCHAR(50) DEFAULT '',
    `leg_press` VARCHAR(50) DEFAULT '',
    `romanian_deadlift` VARCHAR(50) DEFAULT '',
    `rdl` VARCHAR(50) DEFAULT '',
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($ensureSQL);

// helper: fetch progress for this member
$selectSQL = "SELECT * FROM member_progress WHERE member_id = ?";
$stmt = $conn->prepare($selectSQL);
if (!$stmt) {
    die("Database prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$progress = $result->fetch_assoc() ?: null;
$stmt->close();

$saved = false;
$err = '';

// handle POST - update/insert
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bench_press = $_POST['bench_press'] ?? '';
    $incline_press = $_POST['incline_press'] ?? '';
    $decline_press = $_POST['decline_press'] ?? '';
    $chest_fly = $_POST['chest_fly'] ?? '';
    $overhead_press = $_POST['overhead_press'] ?? '';
    $lateral_raises = $_POST['lateral_raises'] ?? '';
    $deadlift = $_POST['deadlift'] ?? '';
    $lat_pulldown = $_POST['lat_pulldown'] ?? '';
    $weight_now = $_POST['weight_now'] ?? '';
    $weight_before = $_POST['weight_before'] ?? '';
    $squat = $_POST['squat'] ?? '';
    $leg_press = $_POST['leg_press'] ?? '';
    $romanian_deadlift = $_POST['romanian_deadlift'] ?? '';
    $rdl = $_POST['rdl'] ?? '';

    $check = $conn->prepare("SELECT id FROM member_progress WHERE member_id = ?");
    if ($check) {
        $check->bind_param("i", $member_id);
        $check->execute();
        $cr = $check->get_result();
        $exists = ($cr && $cr->num_rows > 0);
        $check->close();

        if ($exists) {
            $updateSQL = "UPDATE member_progress SET 
                bench_press = ?, incline_press = ?, decline_press = ?, chest_fly = ?, overhead_press = ?, lateral_raises = ?,
                deadlift = ?, lat_pulldown = ?, weight_now = ?, weight_before = ?,
                squat = ?, leg_press = ?, romanian_deadlift = ?, rdl = ?
                WHERE member_id = ?";
            $upd = $conn->prepare($updateSQL);
            if ($upd) {
                $types = str_repeat('s', 14) . 'i';
                $upd->bind_param($types,
                    $bench_press, $incline_press, $decline_press, $chest_fly, $overhead_press, $lateral_raises,
                    $deadlift, $lat_pulldown, $weight_now, $weight_before,
                    $squat, $leg_press, $romanian_deadlift, $rdl,
                    $member_id
                );
                if (!$upd->execute()) $err = "DB error (execute update): " . $upd->error;
                $upd->close();
                $saved = !$err;
            }
        } else {
            $insertSQL = "INSERT INTO member_progress 
                (member_id, bench_press, incline_press, decline_press, chest_fly, overhead_press, lateral_raises,
                 deadlift, lat_pulldown, weight_now, weight_before, squat, leg_press, romanian_deadlift, rdl)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $ins = $conn->prepare($insertSQL);
            if ($ins) {
                $types = 'i' . str_repeat('s', 14);
                $ins->bind_param($types,
                    $member_id, $bench_press, $incline_press, $decline_press, $chest_fly, $overhead_press, $lateral_raises,
                    $deadlift, $lat_pulldown, $weight_now, $weight_before, $squat, $leg_press, $romanian_deadlift, $rdl
                );
                if (!$ins->execute()) $err = "DB error (execute insert): " . $ins->error;
                $ins->close();
                $saved = !$err;
            }
        }
    }

    // refetch updated row
    $stmt2 = $conn->prepare($selectSQL);
    if ($stmt2) {
        $stmt2->bind_param("i", $member_id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        $progress = $res2->fetch_assoc() ?: null;
        $stmt2->close();
    }
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
</style>
</head>
<body>
<!-- Sidebar (same as dashboard) -->
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

  <?php if ($saved): ?>
    <div class="card card-margin-bottom"><div class="save-note">Progress saved successfully.</div></div>
  <?php elseif ($err): ?>
    <div class="card card-margin-bottom"><div class="error-note"><?php echo htmlspecialchars($err); ?></div></div>
  <?php endif; ?>

  <form method="post" class="card card-margin-bottom" style="padding:16px;">
    <div class="stats-container">
      <!-- Chest / Shoulders -->
      <div class="stat-column">
        <h3>Chest / Shoulders</h3>
        <label>Bench Press</label>
        <input name="bench_press" value="<?php echo htmlspecialchars($progress['bench_press'] ?? ''); ?>">
        <label>Incline Bench Press</label>
        <input name="incline_press" value="<?php echo htmlspecialchars($progress['incline_press'] ?? ''); ?>">
        <label>Decline Bench Press</label>
        <input name="decline_press" value="<?php echo htmlspecialchars($progress['decline_press'] ?? ''); ?>">
        <label>Chest Fly</label>
        <input name="chest_fly" value="<?php echo htmlspecialchars($progress['chest_fly'] ?? ''); ?>">
        <label>Overhead Press</label>
        <input name="overhead_press" value="<?php echo htmlspecialchars($progress['overhead_press'] ?? ''); ?>">
        <label>Lateral Raises</label>
        <input name="lateral_raises" value="<?php echo htmlspecialchars($progress['lateral_raises'] ?? ''); ?>">
      </div>

      <!-- Back / Biceps -->
      <div class="stat-column">
        <h3>Back / Biceps</h3>
        <div style="margin-bottom:16px; padding-bottom:12px; border-bottom:1px solid rgba(255,255,255,0.1);">
          <label>Deadlift</label>
          <input name="deadlift" type="number" step="0.1" min="0"
                 value="<?php echo htmlspecialchars($progress['deadlift'] ?? ''); ?>">
          <label>Lat Pulldown</label>
          <input name="lat_pulldown" type="number" step="0.1" min="0"
                 value="<?php echo htmlspecialchars($progress['lat_pulldown'] ?? ''); ?>">
        </div>
        <div>
          <h4>Weight Tracking</h4>
          <label>Weight Now (kg)</label>
          <input name="weight_now" type="number" step="0.1" min="0"
                 value="<?php echo htmlspecialchars($progress['weight_now'] ?? ''); ?>">
          <label>Weight Last Month (kg)</label>
          <input name="weight_before" type="number" step="0.1" min="0"
                 value="<?php echo htmlspecialchars($progress['weight_before'] ?? ''); ?>">
        </div>
      </div>

      <!-- Legs -->
      <div class="stat-column">
        <h3>Legs</h3>
        <label>Squat</label>
        <input name="squat" value="<?php echo htmlspecialchars($progress['squat'] ?? ''); ?>">
        <label>Leg Press</label>
        <input name="leg_press" value="<?php echo htmlspecialchars($progress['leg_press'] ?? ''); ?>">
        <label>Romanian Deadlift</label>
        <input name="romanian_deadlift" value="<?php echo htmlspecialchars($progress['romanian_deadlift'] ?? ''); ?>">
        <label>RDL</label>
        <input name="rdl" value="<?php echo htmlspecialchars($progress['rdl'] ?? ''); ?>">
      </div>
    </div>
    <div style="margin-top:14px;">
      <button type="submit" class="action-btn edit-btn">Save Progress</button>
    </div>
  </form>
</div>
</body>
</html>
