<?php
// employee-schedule.php  (Duty Roster version — date-filtered view)
require_once '../includes/session_check.php';
include '../db.php';

check_session(['employee']);

// Use session employee id if available (not required for adding duties)
$session_employee_id = isset($_SESSION['employee_id']) ? (int) $_SESSION['employee_id'] : null;

// Handle reset roster
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_roster'])) {
    // Truncate schedules table (use with care)
    if ($conn->query("TRUNCATE TABLE schedules") === TRUE) {
        $success = "Roster has been reset successfully.";
    } else {
        $error = "Failed to reset roster: " . htmlspecialchars($conn->error);
    }
}

// Handle add duty form submission (self-contained)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_duty'])) {
    // Collect and validate inputs
    $date = $_POST['date'] ?? '';
    $shift_time = $_POST['shift_time'] ?? '';
    $employee_id = isset($_POST['employee_id']) ? (int) $_POST['employee_id'] : 0;
    $job_role = trim($_POST['job_role'] ?? '');
    $job_role_custom = trim($_POST['job_role_custom'] ?? '');

    // If user selected "Other" and provided custom role, use it
    if ($job_role === 'Other' && $job_role_custom !== '') {
        $job_role = $job_role_custom;
    }

    // Basic validation
    if (empty($date) || empty($shift_time) || $employee_id <= 0 || empty($job_role)) {
        $error = "Please fill in all required fields.";
    } else {
        // Optional: convert shift_time to normalized values (Morning, Afternoon, Night)
        $allowed_shifts = ['Morning', 'Afternoon', 'Night'];
        if (!in_array($shift_time, $allowed_shifts)) {
            $error = "Invalid shift selected.";
        } else {
            // Check duplicate: same employee, same date, same shift
            $dup_check_sql = "SELECT COUNT(*) AS cnt FROM schedules WHERE date = ? AND shift_time = ? AND employee_id = ?";
            $stmt = $conn->prepare($dup_check_sql);
            if ($stmt) {
                $stmt->bind_param("ssi", $date, $shift_time, $employee_id);
                $stmt->execute();
                $res = $stmt->get_result();
                $dup = ($res && $row = $res->fetch_assoc()) ? (int)$row['cnt'] : 0;
                $stmt->close();

                if ($dup > 0) {
                    $error = "This employee is already assigned to that date and shift.";
                } else {
                    // Insert duty
                    $insert_sql = "INSERT INTO schedules (date, shift_time, job_role, employee_id) VALUES (?, ?, ?, ?)";
                    $stmt2 = $conn->prepare($insert_sql);
                    if ($stmt2) {
                        $stmt2->bind_param("sssi", $date, $shift_time, $job_role, $employee_id);
                        if ($stmt2->execute()) {
                            $success = "Duty added successfully.";
                        } else {
                            $error = "Failed to add duty: " . htmlspecialchars($stmt2->error);
                        }
                        $stmt2->close();
                    } else {
                        $error = "Failed to prepare insert: " . htmlspecialchars($conn->error);
                    }
                }
            } else {
                $error = "Failed to prepare duplicate check: " . htmlspecialchars($conn->error);
            }
        }
    }
}

// Fetch employee list for selection (all active employees)
$employees_stmt = $conn->prepare("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, position FROM employees WHERE status = 'Active' ORDER BY first_name, last_name");
$employees = [];
if ($employees_stmt) {
    $employees_stmt->execute();
    $result = $employees_stmt->get_result();
    if ($result) {
        while ($r = $result->fetch_assoc()) {
            $employees[] = $r;
        }
    }
    $employees_stmt->close();
} else {
    // fallback: try simple query
    $fallback = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, position FROM employees WHERE status = 'Active' ORDER BY first_name, last_name");
    if ($fallback) {
        while ($r = $fallback->fetch_assoc()) $employees[] = $r;
    }
}

// ---------- New: single-date view (default = today) ----------
$selected_date = isset($_GET['date']) && DateTime::createFromFormat('Y-m-d', $_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch roster for the selected date only
$duty_sql = "SELECT s.id, s.date, s.shift_time, s.job_role, s.employee_id, CONCAT(e.first_name, ' ', e.last_name) AS employee_name, e.position
             FROM schedules s
             LEFT JOIN employees e ON s.employee_id = e.id
             WHERE s.date = ?
             ORDER BY FIELD(s.shift_time, 'Morning','Afternoon','Night'), e.first_name, e.last_name";
$stmt = $conn->prepare($duty_sql);
$roster = [];
if ($stmt) {
    $stmt->bind_param("s", $selected_date);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $roster[] = $row;
        }
    }
    $stmt->close();
} else {
    error_log("Prepare failed (duty_sql): " . $conn->error);
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

// Build array grouped by shift for the selected date
$roster_by_shift = [
    'Morning' => [],
    'Afternoon' => [],
    'Night' => []
];
foreach ($roster as $r) {
    $shift = $r['shift_time'];
    if (!isset($roster_by_shift[$shift])) $roster_by_shift[$shift] = [];
    $roster_by_shift[$shift][] = $r;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Nexus | Employee - Duty Roster</title>
<link rel="stylesheet" href="employee.css">
<style>
/* small additions to keep it tidy */
.roster-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px; }
.roster-card { background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); color:#333; } /* ✅ FIX APPLIED HERE */
.shift-block { margin-bottom: 8px; padding: 8px; border-radius: 6px; background: #f6f8fa; color:#333; } /* ✅ FIX APPLIED HERE */
.shift-label { font-weight: 600; margin-bottom: 6px; }
.success-msg { color: green; padding: 8px 0; }
.error-msg { color: #c0392b; padding: 8px 0; }
.form-row { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
.form-row > * { flex: 1 1 200px; }
.small { font-size: 0.95em; color:#666; }
.button { padding:8px 12px; border-radius:6px; border:none; cursor:pointer; }
.btn-primary { background:#007bff; color:#fff; }
.btn-danger { background:#e74c3c; color:#fff; }
.btn-secondary { background:#6c757d; color:#fff; }

.view-date-wrap { display:flex; gap:12px; align-items:center; margin-bottom:12px; }
.view-date-wrap input[type="date"] { padding:6px; border-radius:4px; border:1px solid #ccc; }
.view-date-wrap button { padding:6px 10px; }
</style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">NEXUS</div>
    <ul class="nav-menu">
        <li><a href="employee-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
        <li><a href="employee-members.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
        <li><a href="employee-add-member.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Add Member" class="nav-icon"> Add Member</a></li>
        <li class="active"><a href="employee-schedule.php"><img src="../images/icons/dashboard-classes-icon.svg" alt="Roster" class="nav-icon"> Duty Roster</a></li>
        <li><a href="employee-fitness-plans.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" alt="Fitness Plans" class="nav-icon"> Fitness Plans</a></li>
    </ul>
    <div class="logout-container">
        <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Duty Roster</h2>
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
        const dateStr = now.toLocaleDateString(undefined, options);
        const timeStr = now.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('dashboard-datetime').textContent = `${dateStr} | ${timeStr}`;
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();
    </script>

    <!-- Messages -->
    <?php if (!empty($success)): ?><p class="success-msg"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>
    <?php if (!empty($error)): ?><p class="error-msg"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>

    <!-- Controls: Reset roster -->
    <form method="POST" style="margin-bottom:12px;">
        <button type="submit" name="reset_roster" class="button btn-danger" onclick="return confirm('This will remove all roster entries. Continue?')">Reset Roster</button>
    </form>

    <!-- Add New Duty Form -->
    <div class="card card-margin-bottom">
        <div class="employee-table-title">Add Duty</div>
        <form method="POST" style="padding: 16px; display: grid; gap: 12px;">
            <input type="hidden" name="add_duty" value="1" />
            <div class="form-row">
                <div>
                    <label>Date</label><br>
                    <input type="date" name="date" required value="<?php echo htmlspecialchars($selected_date); ?>">
                    <div class="small">Pick the duty date (view uses selected date)</div>
                </div>
                <div>
                    <label>Shift</label><br>
                    <select name="shift_time" required>
                        <option value="">-- Select Shift --</option>
                        <option value="Morning">Morning (6:00 AM - 12:00 PM)</option>
                        <option value="Afternoon">Afternoon (1:00 PM - 6:00 PM)</option>
                        <option value="Night">Night (6:00 PM - 12:00 AM)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Employee</label><br>
                    <select name="employee_id" required>
                        <option value="">-- Select Employee --</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?php echo (int)$emp['id']; ?>">
                                <?php echo htmlspecialchars($emp['full_name'] . ($emp['position'] ? " — {$emp['position']}" : '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Job Role</label><br>
                    <select name="job_role" id="job_role_select" required>
                        <option value="">-- Select Role --</option>
                        <option value="Trainer">Trainer</option>
                        <option value="Receptionist">Receptionist</option>
                        <option value="Cleaner">Cleaner</option>
                        <option value="Front Desk">Front Desk</option>
                        <option value="Coach">Coach</option>
                        <option value="Other">Other (custom)</option>
                    </select>
                </div>
            </div>

            <div id="job_role_custom_wrap" style="display:none;">
                <label>Custom Job Role</label>
                <input type="text" name="job_role_custom" placeholder="Enter custom job role (e.g. 'Manager')" />
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" class="button btn-primary">Add Duty</button>
                <button type="reset" class="button btn-secondary">Clear</button>
            </div>
        </form>
    </div>

    <!-- Date View Control -->
    <div class="view-date-wrap">
        <form method="GET" style="display:inline-flex; align-items:center; gap:8px;">
            <label for="view_date">View Date</label>
            <input type="date" id="view_date" name="date" value="<?php echo htmlspecialchars($selected_date); ?>">
            <button type="submit" class="button btn-secondary">Show</button>
        </form>
        <div style="margin-left:auto;"><strong>Showing:</strong> <?php echo htmlspecialchars(date('l, M j, Y', strtotime($selected_date))); ?></div>
    </div>

    <!-- Roster Grid for selected date -->
<div class="card">
    <div class="employee-table-title">Duty Roster (<?php echo htmlspecialchars(date('M j, Y', strtotime($selected_date))); ?>)</div>
    <div style="padding:12px;">
        <div class="roster-grid">
            <?php
            // Loop through each shift
            foreach (['Morning','Afternoon','Night'] as $shift):
                $shift_duties = $roster_by_shift[$shift] ?? [];
            ?>
                <div class="roster-card">
                    <div style="font-weight:700;"><?php echo htmlspecialchars($shift . ' — ' . shift_label_to_time($shift)); ?></div>
                    <hr>
                    <div class="shift-block">
                        <?php if (!empty($shift_duties)): ?>
                            <?php foreach ($shift_duties as $duty): ?>
                                <div style="padding:6px; margin-bottom:6px; border-radius:6px; background:#fff; box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                                    <div style="font-weight:600;"><?php echo htmlspecialchars($duty['employee_name'] ?? 'Unknown'); ?></div>
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

    <!-- Notes Section -->
    <div class="card" style="margin-top:12px;">
        <div class="employee-table-title">Notes</div>
        <p class="p-margin-top">
            ✅ Employees are required to report at least 15 minutes before shift.<br>
            ✅ In-charge must update attendance logs.<br>
            ✅ For changes, contact the Admin.
        </p>
    </div>
</div>

<script>
// show/hide custom role input when "Other" selected
document.getElementById('job_role_select').addEventListener('change', function() {
    var wrap = document.getElementById('job_role_custom_wrap');
    if (this.value === 'Other') wrap.style.display = 'block'; else wrap.style.display = 'none';
});
</script>
</body>
</html>
