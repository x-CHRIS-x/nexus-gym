<?php
// employee-dashboard.php
include '../includes/session_check.php';
include '../db.php';

check_session(['employee']);

// Use logged in employee id if available
$employee_id = isset($_SESSION['employee_id']) ? (int)$_SESSION['employee_id'] : 1;

$today = date('Y-m-d');

/**
 * Helper: safe fetch single value from prepared statement
 * $stmt should be a prepared & executed mysqli_stmt that returns a result set.
 */
function fetch_single_value($stmt, $key, $default = 0) {
    if (!$stmt) return $default;
    $res = $stmt->get_result();
    if ($res && $row = $res->fetch_assoc()) {
        return isset($row[$key]) ? $row[$key] : $default;
    }
    return $default;
}

/* ---------------------------
Classes Today (Count from Today's Class Bookings)
--------------------------- */
$today_day = date('l'); // current day name
$classes_today_query = "SELECT COUNT(*) as total_classes
                        FROM fitness_classes
                        WHERE day_of_week = ?";
$stmt = $conn->prepare($classes_today_query);
if ($stmt) {
    $stmt->bind_param("s", $today_day);
    $stmt->execute();
    $res = $stmt->get_result();
    $classes_today = ($res && $row = $res->fetch_assoc()) ? (int)$row['total_classes'] : 0;
    $stmt->close();
} else {
    error_log("Prepare failed (classes_today_query): " . $conn->error);
    $classes_today = 0;
}

/* ---------------------------
Assigned Employees in Roster (Modified)
--------------------------- */
$assigned_members_query = "SELECT COUNT(DISTINCT employee_id) AS assigned_count 
                           FROM schedules";
$stmt = $conn->prepare($assigned_members_query);
if ($stmt) {
    $stmt->execute();
    $assigned_members = fetch_single_value($stmt, 'assigned_count', 0);
    $stmt->close();
} else {
    error_log("Prepare failed (assigned_members_query): " . $conn->error);
    $assigned_members = 0;
}

/* ---------------------------
Total Sessions (Updated to count all member attendances)
--------------------------- */
$total_sessions_query = "SELECT COUNT(*) as total FROM member_attendance";
$stmt = $conn->prepare($total_sessions_query);
if ($stmt) {
    $stmt->execute();
    $sessions_result = $stmt->get_result();
    if ($sessions_result && $row = $sessions_result->fetch_assoc()) {
        $total_sessions = (int) $row['total'];
    } else {
        $total_sessions = 0;
    }
    $stmt->close();
} else {
    error_log("Prepare failed (total_sessions_query): " . $conn->error);
    $total_sessions = 0;
}

/* ---------------------------
Total Members (instead of Class Days)
--------------------------- */
$members_query = "SELECT COUNT(*) as total_members FROM members";
$stmt = $conn->prepare($members_query);
if ($stmt) {
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $row = $res->fetch_assoc()) {
        $class_days = (int) $row['total_members']; // reuse $class_days variable
    } else {
        $class_days = 0;
    }
    $stmt->close();
} else {
    error_log("Prepare failed (members_query): " . $conn->error);
    $class_days = 0;
}

/* ---------------------------
Upcoming Schedule (limit 4)
--------------------------- */
$upcoming_schedule = "SELECT s.id, s.date, s.shift_time, s.job_role, 
                    GROUP_CONCAT(DISTINCT CONCAT(m.first_name, ' ', m.last_name) SEPARATOR ', ') as assigned_members
                    FROM schedules s
                    LEFT JOIN notifications n ON s.employee_id = n.employee_id
                    LEFT JOIN members m ON n.member_id = m.id
                    WHERE s.employee_id = ? AND s.date >= CURRENT_DATE()
                    GROUP BY s.id, s.date, s.shift_time, s.job_role
                    ORDER BY s.date, s.shift_time
                    LIMIT 4";
$stmt = $conn->prepare($upcoming_schedule);
$schedule_result = false;
if ($stmt) {
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $schedule_result = $stmt->get_result();
    $stmt->close();
} else {
    error_log("Prepare failed (upcoming_schedule): " . $conn->error);
}

/* ---------------------------
Duty Roster Today (grouped by shift)
--------------------------- */
$roster_query = "SELECT s.shift_time, s.job_role, e.first_name, e.last_name
                 FROM schedules s
                 INNER JOIN employees e ON s.employee_id = e.id
                 WHERE s.date = ? 
                 AND e.position NOT IN ('Trainer', 'Coach')
                 AND s.job_role NOT IN ('Trainer', 'Coach')
                 ORDER BY FIELD(s.shift_time, 'Morning','Afternoon','Night'), e.first_name, e.last_name";
$stmt = $conn->prepare($roster_query);
$roster_by_shift = ['Morning'=>[], 'Afternoon'=>[], 'Night'=>[]];
if ($stmt) {
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $shift = $row['shift_time'];
            if (!isset($roster_by_shift[$shift])) $roster_by_shift[$shift] = [];
            $roster_by_shift[$shift][] = $row;
        }
    }
    $stmt->close();
}

// Helper: map shift label -> human readable time
function shift_label_to_time($shift) {
    switch ($shift) {
        case 'Morning': return '6:00 AM - 12:00 PM';
        case 'Afternoon': return '1:00 PM - 6:00 PM';
        case 'Night': return '6:00 PM - 12:00 AM';
        default: return $shift;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nexus | Employee Dashboard</title>
<link rel="stylesheet" href="employee.css">
<style>
/* small additions */
.status-active { color: green; font-weight:600; }
.status-inactive { color:#888; font-weight:400; }
.roster-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:12px; }
.roster-card { background:#fff; border-radius:8px; padding:12px; box-shadow:0 1px 3px rgba(0,0,0,0.08); color:#333; }
.shift-block { margin-bottom:8px; padding:8px; border-radius:6px; background:#f6f8fa; color:#333; }
.shift-label { font-weight:600; margin-bottom:6px; }
</style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">NEXUS</div>
    <ul class="nav-menu">
        <li class="active"><a href="employee-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
        <li><a href="employee-members.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
        <li><a href="employee-add-member.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Add Member" class="nav-icon"> Add Member</a></li>
        <li><a href="employee-schedule.php"><img src="../images/icons/dashboard-classes-icon.svg" alt="Schedule" class="nav-icon"> Schedule</a></li>
        <li><a href="employee-fitness-plans.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" alt="Fitness Plans" class="nav-icon"> Fitness Plans</a></li>
    </ul>
    <div class="logout-container">
        <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Employee Dashboard</h2>
        <div class="dashboard-datetime dashboard-datetime-style" id="dashboard-datetime"></div>
        <div class="user-profile">
            <img src="../images/profile pictures/default-profile.svg" alt="User">
            <span>Employee</span>
        </div>
    </div>
<script>
// Live date and time
function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const timeStr = now.toLocaleTimeString(undefined, { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    const dateStr = now.toLocaleDateString(undefined, options);
    document.getElementById('dashboard-datetime').textContent = `${dateStr} | ${timeStr}`;
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>

<!-- Summary Cards -->
<div class="dashboard-summary-row">
    <div class="dashboard-card">
        <img src="../images/icons/dashboard-classes-icon.svg" alt="Classes Today" class="summary-icon">
        <div class="summary-number"><?php echo htmlspecialchars($classes_today); ?></div>
        <div class="summary-label">Classes Today</div>
    </div>
    <div class="dashboard-card">
        <img src="../images/icons/dashboard-members-icon.svg" alt="Assigned Members" class="summary-icon">
        <div class="summary-number"><?php echo htmlspecialchars($assigned_members); ?></div>
        <div class="summary-label">Assigned Employee</div>
    </div>
    <div class="dashboard-card">
        <img src="../images/icons/dashboard-classes-icon.svg" alt="Total Members" class="summary-icon">
        <div class="summary-number"><?php echo htmlspecialchars($class_days); ?></div>
        <div class="summary-label">Total Members</div>
    </div>
    <div class="dashboard-card">
        <img src="../images/icons/dashboard-progress-icon.svg" alt="Total Sessions" class="summary-icon">
        <div class="summary-number"><?php echo htmlspecialchars($total_sessions); ?></div>
        <div class="summary-label">Total Sessions</div>
    </div>
</div>

<!-- Today's Class Bookings -->
<div class="card" style="margin-top: 15px;">
    <div class="employee-table-title">Today's Class Bookings (<?php echo date('l, F d, Y'); ?>)</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Class Name</th>
                    <th>Trainer</th>
                    <th>Bookings</th>
                    <th>Capacity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $classes_query = "SELECT 
                    fc.id,
                    fc.name,
                    fc.time_slot,
                    fc.capacity,
                    CONCAT(e.first_name, ' ', e.last_name) as trainer_name,
                    COUNT(cb.id) as current_bookings
                    FROM fitness_classes fc
                    LEFT JOIN employees e ON fc.trainer_id = e.id
                    LEFT JOIN class_bookings cb ON fc.id = cb.class_id 
                        AND cb.booking_date = CURDATE()
                        AND cb.status = 'booked'
                    WHERE fc.day_of_week = ?
                    GROUP BY fc.id, fc.name, fc.time_slot, fc.capacity, trainer_name
                    ORDER BY FIELD(fc.time_slot, 'Morning', 'Afternoon', 'Evening')";
                
                $stmt = $conn->prepare($classes_query);
                $stmt->bind_param("s", $today_day);
                $stmt->execute();
                $classes_result = $stmt->get_result();

                if ($classes_result->num_rows > 0) {
                    while($row = $classes_result->fetch_assoc()) {
                        $time_slot = '';
                        switch($row['time_slot']) {
                            case 'Morning': $time_slot = '7:00 AM - 9:00 AM'; break;
                            case 'Afternoon': $time_slot = '2:00 PM - 4:00 PM'; break;
                            case 'Evening': $time_slot = '6:00 PM - 8:00 PM'; break;
                        }
                        echo "<tr>
                                <td>{$time_slot}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['trainer_name']}</td>
                                <td>{$row['current_bookings']}</td>
                                <td>{$row['capacity']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No classes scheduled for today</td></tr>";
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Staff Duty Roster - Non-Training Staff -->
<div class="card" style="margin-top: 15px;">
    <div class="employee-table-title">Staff Duty Roster (<?php echo date('l, F d, Y'); ?>)</div>
    <div style="padding:12px;">
        <div class="roster-grid">
            <?php foreach (['Morning','Afternoon','Night'] as $shift): ?>
                <div class="roster-card">
                    <div style="font-weight:700;"><?php echo htmlspecialchars($shift . ' — ' . shift_label_to_time($shift)); ?></div>
                    <hr>
                    <div class="shift-block">
                        <?php if (!empty($roster_by_shift[$shift])): ?>
                            <?php foreach ($roster_by_shift[$shift] as $duty): ?>
                                <div style="padding:6px; margin-bottom:6px; border-radius:6px; background:#fff; box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                                    <div style="font-weight:600;"><?php echo htmlspecialchars($duty['first_name'] . ' ' . $duty['last_name']); ?></div>
                                    <div class="small"><?php echo htmlspecialchars($duty['job_role']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="small">No one assigned</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Available Coaches View (View-Only) -->
<div class="card" style="margin-top: 15px;">
    <div class="employee-table-title">Available Coaches</div>
    <div style="padding:12px;">
        <div class="roster-grid">
            <?php
            // Fetch all active coaches/trainers
            $coachQuery = "
                SELECT 
                    e.id, 
                    CONCAT(e.first_name, ' ', e.last_name) AS full_name, 
                    e.position, 
                    CASE WHEN EXISTS (
                        SELECT 1 FROM coach_availability ca WHERE ca.employee_id = e.id
                    ) THEN 1 ELSE 0 END AS is_available
                FROM employees e
                WHERE e.status = 'Active'
                AND (e.position LIKE '%Coach%' OR e.position LIKE '%Trainer%')
                ORDER BY e.first_name, e.last_name
            ";
            $coachResult = $conn->query($coachQuery);

            if ($coachResult && $coachResult->num_rows > 0):
                while($coach = $coachResult->fetch_assoc()):
            ?>
                    <div class="roster-card" style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <div style="font-weight:600;"><?php echo htmlspecialchars($coach['full_name']); ?></div>
                            <div class="small"><?php echo htmlspecialchars($coach['position']); ?></div>
                        </div>
                        <div style="padding:4px 8px; border-radius:4px; color:#fff; font-weight:600; 
                            background:<?php echo $coach['is_available'] ? 'green' : 'red'; ?>;">
                            <?php echo $coach['is_available'] ? 'Available' : 'Unavailable'; ?>
                        </div>
                    </div>
            <?php
                endwhile;
            else:
                echo '<div class="small">No coaches available</div>';
            endif;
            ?>
        </div>
    </div>
</div>

</div>
</body>
</html>
