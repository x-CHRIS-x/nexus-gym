<?php
include '../db.php';
session_start();

// Handle reset roster
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_roster'])) {
    $conn->query("TRUNCATE TABLE schedules"); // Clear all schedules
    $success = "Roster has been reset successfully.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_schedule'])) {
    $employee_id = intval($_POST['employee_id']);
    $shift_time  = $conn->real_escape_string($_POST['shift_time']);
    $job_role    = $conn->real_escape_string($_POST['job_role']);
    $date        = $conn->real_escape_string($_POST['date']);

    // Prevent duplicate shift for the same employee on the same date
    $check = $conn->query("SELECT * FROM schedules WHERE employee_id=$employee_id AND date='$date'");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO schedules (employee_id, shift_time, job_role, date) 
                VALUES ($employee_id, '$shift_time', '$job_role', '$date')";
        if (!$conn->query($sql)) {
            $error = "Error assigning schedule: " . $conn->error;
        }
    } else {
        $error = "This employee already has a shift assigned on this date.";
    }
}

// Fetch all employees
$employees = $conn->query("SELECT id, full_name FROM employees");

// Fetch all schedules
$schedules = $conn->query("
    SELECT s.date, s.shift_time, e.full_name AS in_charge, s.job_role
    FROM schedules s
    INNER JOIN employees e ON s.employee_id = e.id
    ORDER BY s.date ASC, s.shift_time ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nexus | Employee - Schedule</title>
<link rel="stylesheet" href="employee.css">
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">NEXUS</div>
    <ul class="nav-menu">
        <li><a href="employee-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
        <li><a href="employee-members.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
        <li class="active"><a href="employee-schedule.php"><img src="../images/icons/dashboard-classes-icon.svg" alt="Schedule" class="nav-icon"> Schedule</a></li>
        <li><a href="employee-fitness-plans.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" alt="Fitness Plans" class="nav-icon"> Fitness Plans</a></li>
    </ul>
    <div class="logout-container">
        <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="header">
        <h2>Employee Schedule</h2>
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

    <!-- Reset Roster -->
    <form method="POST" style="margin-bottom:15px;">
        <button type="submit" name="reset_roster" style="background:red; color:white; padding:8px 12px; border:none; border-radius:5px; cursor:pointer;">
            Reset Roster
        </button>
    </form>
    <?php if(isset($success)): ?>
        <p style="color:green; padding:10px;"><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Assign Employee Schedule -->
    <div class="card card-margin-bottom">
        <div class="employee-table-title">Assign Employee to Shift</div>
        <?php if(isset($error)): ?>
            <p style="color:red; padding:10px;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label>Date:</label>
            <input type="date" name="date" required>

            <label>Shift Time:</label>
            <input type="text" name="shift_time" placeholder="e.g. 08:00 - 12:00" required>

            <label>Employee:</label>
            <select name="employee_id" required>
                <option value="">-- Select Employee --</option>
                <?php while($emp = $employees->fetch_assoc()): ?>
                    <option value="<?php echo $emp['id']; ?>"><?php echo htmlspecialchars($emp['full_name']); ?></option>
                <?php endwhile; ?>
            </select>

            <label>Job Role:</label>
            <input type="text" name="job_role" placeholder="e.g. Trainer" required>

            <button type="submit" name="assign_schedule">Assign</button>
        </form>
    </div>

    <!-- Duty Roster -->
    <div class="card card-margin-bottom">
        <div class="employee-table-title">Duty Roster</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>In-Charge</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($schedules && $schedules->num_rows > 0): ?>
                        <?php while($row = $schedules->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date("M d, Y", strtotime($row['date'])); ?></td>
                                <td><?php echo htmlspecialchars($row['shift_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['in_charge']); ?></td>
                                <td><?php echo htmlspecialchars($row['job_role']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No schedules found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="card">
        <div class="employee-table-title">Notes</div>
        <p class="p-margin-top">
            ✅ Employees are required to report at least 15 minutes before shift.<br>
            ✅ In-charge must update attendance logs.<br>
            ✅ For changes, contact the Admin.
        </p>
    </div>
</div>
</body>
</html>
