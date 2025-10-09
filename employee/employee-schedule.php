<?php
require_once '../includes/session_check.php';
include '../db.php';

check_session(['employee']);

// Handle reset roster
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_roster'])) {
    $conn->query("TRUNCATE TABLE schedules"); // Clear all schedules
    $success = "Roster has been reset successfully.";
}

// Form submission is now handled in add_class.php

// Fetch all employees
$employees = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) as full_name FROM employees");

// Fetch all fitness classes with schedules
$schedules = $conn->query("
    SELECT 
        fc.day_of_week,
        fc.time_slot,
        CONCAT(e.first_name, ' ', e.last_name) AS in_charge,
        e.position as job_role,
        fc.name as class_name,
        CASE 
            WHEN fc.time_slot = 'Morning' THEN '7:00 AM - 9:00 AM'
            WHEN fc.time_slot = 'Afternoon' THEN '2:00 PM - 4:00 PM'
            WHEN fc.time_slot = 'Evening' THEN '6:00 PM - 8:00 PM'
        END as shift_time
    FROM fitness_classes fc
    INNER JOIN employees e ON fc.trainer_id = e.id
    WHERE e.status = 'Active'
    ORDER BY 
        FIELD(fc.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
        FIELD(fc.time_slot, 'Morning', 'Afternoon', 'Evening')
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
        <li><a href="employee-add-member.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Add Member" class="nav-icon"> Add Member</a></li>
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

    <!-- Add New Class -->
    <div class="card card-margin-bottom">
        <div class="employee-table-title">Add New Class</div>
        <?php if(isset($error)): ?>
            <p style="color:red; padding:10px;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="add_class.php">
            <label>Day of Week:</label>
            <select name="day_of_week" required>
                <option value="">-- Select Day --</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>

            <label>Time Slot:</label>
            <select name="time_slot" required>
                <option value="">-- Select Time --</option>
                <option value="Morning">Morning (7:00 AM - 9:00 AM)</option>
                <option value="Afternoon">Afternoon (2:00 PM - 4:00 PM)</option>
                <option value="Evening">Evening (6:00 PM - 8:00 PM)</option>
            </select>

            <label>Class Name:</label>
            <input type="text" name="class_name" placeholder="e.g. Yoga, HIIT, Strength Training" required>

            <label>Trainer:</label>
            <select name="trainer_id" required>
                <option value="">-- Select Trainer --</option>
                <?php 
                $trainers = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) as full_name 
                                        FROM employees 
                                        WHERE status = 'Active' 
                                        AND (position = 'Trainer' OR position = 'Coach')
                                        ORDER BY first_name, last_name");
                while($trainer = $trainers->fetch_assoc()): 
                ?>
                    <option value="<?php echo $trainer['id']; ?>"><?php echo htmlspecialchars($trainer['full_name']); ?></option>
                <?php endwhile; ?>
            </select>

            <label>Class Description:</label>
            <textarea name="description" placeholder="Brief description of the class" required></textarea>

            <label>Capacity:</label>
            <input type="number" name="capacity" min="1" max="30" value="15" required>

            <button type="submit" name="add_class">Add Class</button>
        </form>
    </div>

    <!-- Class Schedule -->
    <div class="card card-margin-bottom">
        <div class="employee-table-title">Class Schedule</div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Class</th>
                        <th>Trainer</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($schedules && $schedules->num_rows > 0): ?>
                        <?php while($row = $schedules->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['day_of_week']); ?></td>
                                <td><?php echo htmlspecialchars($row['shift_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['in_charge']); ?></td>
                                <td><?php echo htmlspecialchars($row['job_role']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No classes scheduled.</td>
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
