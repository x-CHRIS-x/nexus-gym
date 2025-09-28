<?php
include '../db.php';
session_start();

// Get employee ID from session (you'll need to set this during login)
$employee_id = 1; // Temporarily hardcoded, should come from $_SESSION['employee_id']

// Fetch counts for summary cards
$today = date('Y-m-d');

// Count scheduled classes/sessions for today
$schedule_query = "SELECT COUNT(*) as class_count FROM schedules 
                  WHERE employee_id = ? AND date = ? AND job_role LIKE '%trainer%'";
$stmt = $conn->prepare($schedule_query);
$stmt->bind_param("is", $employee_id, $today);
$stmt->execute();
$classes_today = $stmt->get_result()->fetch_assoc()['class_count'];

// Count assigned members (from notifications table where message = 'hired')
$members_query = "SELECT COUNT(DISTINCT member_id) as member_count FROM notifications 
                 WHERE employee_id = ? AND message = 'hired'";
$stmt = $conn->prepare($members_query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$assigned_members = $stmt->get_result()->fetch_assoc()['member_count'];

// Fetch upcoming schedule
$upcoming_schedule = "SELECT s.date, s.shift_time, s.job_role, 
                     GROUP_CONCAT(m.full_name SEPARATOR ', ') as assigned_members
                     FROM schedules s
                     LEFT JOIN notifications n ON s.employee_id = n.employee_id
                     LEFT JOIN members m ON n.member_id = m.id
                     WHERE s.employee_id = ? AND s.date >= CURRENT_DATE()
                     GROUP BY s.id
                     ORDER BY s.date, s.shift_time
                     LIMIT 4";
$stmt = $conn->prepare($upcoming_schedule);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$schedule_result = $stmt->get_result();

// Fetch assigned members with their details
$assigned_members_query = "SELECT DISTINCT m.full_name, m.membership_type, 
                          CASE 
                            WHEN EXISTS (
                              SELECT 1 FROM schedules s 
                              WHERE s.employee_id = n.employee_id 
                              AND s.date = CURRENT_DATE
                            ) THEN 'Present'
                            ELSE 'Absent'
                          END as attendance
                          FROM notifications n
                          JOIN members m ON n.member_id = m.id
                          WHERE n.employee_id = ? AND n.message = 'hired'";
$stmt = $conn->prepare($assigned_members_query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$members_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Employee Dashboard</title>
    <link rel="stylesheet" href="employee.css">

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
    <script>
    // Live date and time for dashboard
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
        </div>

        <!-- Summary Cards Row -->
        <div class="dashboard-summary-row">
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-classes-icon.svg" alt="Classes Today" class="summary-icon">
                <div class="summary-number"><?php echo $classes_today; ?></div>
                <div class="summary-label">Classes Today</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-members-icon.svg" alt="Assigned Members" class="summary-icon">
                <div class="summary-number"><?php echo $assigned_members; ?></div>
                <div class="summary-label">Assigned Members</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-classes-icon.svg" alt="Available Days" class="summary-icon">
                <div class="summary-number"><?php 
                    $avail_query = "SELECT COUNT(DISTINCT available_day) as days FROM coach_availability WHERE employee_id = $employee_id";
                    $avail_result = $conn->query($avail_query);
                    echo $avail_result->fetch_assoc()['days'];
                ?></div>
                <div class="summary-label">Available Days</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-progress-icon.svg" alt="Total Sessions" class="summary-icon">
                <div class="summary-number"><?php 
                    $sessions_query = "SELECT COUNT(*) as total FROM schedules WHERE employee_id = $employee_id";
                    $sessions_result = $conn->query($sessions_query);
                    echo $sessions_result->fetch_assoc()['total'];
                ?></div>
                <div class="summary-label">Total Sessions</div>
            </div>
        </div>

        <!-- Large Card with Employee Table -->
    <!-- Available Coaches Section -->
    <div class="card card-margin-bottom">
            <div class="employee-table-title">Available Coaches Today</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Coach Name</th>
                            <th>Position</th>
                            <th>Available Times</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Get coaches available today
                        $today_name = date('l'); // Gets day name (Monday, Tuesday, etc.)
                        $available_coaches_query = "SELECT 
                            e.full_name,
                            e.position,
                            ca.available_time,
                            e.status
                            FROM employees e
                            JOIN coach_availability ca ON e.id = ca.employee_id
                            WHERE e.status = 'Active'
                            AND ca.available_day = ?";
                        
                        $stmt = $conn->prepare($available_coaches_query);
                        $search_day = "%$today_name%";
                        $stmt->bind_param("s", $search_day);
                        $stmt->execute();
                        $coaches_result = $stmt->get_result();

                        if ($coaches_result->num_rows > 0) {
                            while ($row = $coaches_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['position']) . "</td>";
                                // Format available times
                                $times = explode(',', $row['available_time']);
                                $formatted_times = array_map(function($time) {
                                    return date('g:i A', strtotime($time));
                                }, $times);
                                echo "<td>" . implode(', ', $formatted_times) . "</td>";
                                echo "<td><span class='status-active'>Available</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align: center;'>No coaches available today</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Duty Roster Section -->
        <div class="card">
            <div class="employee-table-title">Duty Roster</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Shift Time</th>
                            <th>Job Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Get duty roster for the current week
                        $week_start = date('Y-m-d', strtotime('monday this week'));
                        $week_end = date('Y-m-d', strtotime('sunday this week'));
                        
                        $duty_query = "SELECT 
                            date,
                            shift_time,
                            job_role,
                            CASE 
                                WHEN date = CURRENT_DATE THEN 'Today'
                                WHEN date < CURRENT_DATE THEN 'Completed'
                                ELSE 'Upcoming'
                            END as status
                            FROM schedules
                            WHERE employee_id = ?
                            AND date BETWEEN ? AND ?
                            ORDER BY date ASC, shift_time ASC";
                        
                        $stmt = $conn->prepare($duty_query);
                        $stmt->bind_param("iss", $employee_id, $week_start, $week_end);
                        $stmt->execute();
                        $duty_result = $stmt->get_result();

                        if ($duty_result->num_rows > 0) {
                            while ($row = $duty_result->fetch_assoc()) {
                                $status_class = $row['status'] === 'Today' ? 'status-active' : 
                                               ($row['status'] === 'Completed' ? 'status-inactive' : '');
                                echo "<tr>";
                                echo "<td>" . date('l', strtotime($row['date'])) . "</td>";
                                echo "<td>" . date('g:i A', strtotime($row['shift_time'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['job_role']) . "</td>";
                                echo "<td><span class='" . $status_class . "'>" . $row['status'] . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align: center;'>No duties scheduled for this week</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>