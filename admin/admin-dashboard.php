<?php
include '../includes/session_check.php';
include '../db.php';

check_session(['admin']);

$sql = "SELECT * FROM members";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">

</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li class="active"><a href="admin-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li><a href="admin-employees.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Employees" class="nav-icon"> Employees</a></li>
            <li><a href="admin-members.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
            <li><a href="admin-add-member.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Add Member" class="nav-icon"> Add Member</a></li>
            <li><a href="admin-settings.php"><img src="../images/icons/dashboard-settings-icon.svg" alt="Settings" class="nav-icon"> Settings</a></li>
        </ul>
        <div class="logout-container">
            <a href="../logout.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h2>Admin Dashboard</h2>
        <div class="dashboard-datetime dashboard-datetime-style" id="dashboard-datetime"></div>
            <div class="user-profile">
                <img src="../images/profile pictures/default-profile.svg" alt="User">
                <span>Admin</span>
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

        <!-- Top summary cards row -->
        <div class="dashboard-summary-row">
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-members-icon.svg" class="summary-icon summary-icon-38" alt="Total Members">
                <div class="summary-number">
                    <?php
                    $sql = "SELECT COUNT(*) FROM members";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row["COUNT(*)"];
                    ?>
                </div>
                <div class="summary-label">Registered Members</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-members-icon.svg" class="summary-icon summary-icon-38" alt="Total Employees/Trainers">
                <div class="summary-number"><?php
                    $sql = "SELECT COUNT(*) FROM employees where position = 'Trainer'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row["COUNT(*)"];
                    ?></div>
                <div class="summary-label">Active Trainers</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-payment-icon.svg" class="summary-icon summary-icon-38" alt="Active Members">
                <div class="summary-number">
                <?php
                    $sql = "SELECT COUNT(*) FROM members where status = 'Active'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row["COUNT(*)"];
                    ?>
                </div>
                <div class="summary-label">Currently Active Members</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-progress-icon.svg" class="summary-icon summary-icon-38" alt="Revenue Overview">
                <div class="summary-number summary-number-gold">
                    <?php
                    // Calculate total revenue for current month
                    $current_month = date('Y-m');
                    $sql = "SELECT SUM(amount_paid) as total_revenue 
                           FROM member_subscriptions 
                           WHERE DATE_FORMAT(created_at, '%Y-%m') = '$current_month' 
                           AND payment_status = 'paid'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo '₱' . number_format($row['total_revenue'] ?? 0, 2);
                    ?>
                </div>
                <div class="summary-label">Revenue (This Month)
                    </span>
                </div>
            </div>
        </div>

        <!-- Attendance Today & Trainer Assignments Row -->
    <div class="flex-row flex-gap-24 flex-mb-24 flex-wrap">
            <div class="card card-flex-1 card-min-width-220 card-max-width-300">
                <div class="employee-table-title">Attendance Today</div>
                <div class="attendance-today-number">
                    <?php
                    // Get count of today's training sessions
                    $today = date('Y-m-d');
                    $sql = "SELECT COUNT(*) as total_sessions 
                           FROM training_sessions 
                           WHERE session_date = '$today' 
                           AND status = 'completed'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row['total_sessions'] ?? 0;
                    ?>
                </div>
                <div class="attendance-today-label">Check-ins</div>
            </div>
            <div class="card card-flex-2 card-min-width-260">
                <div class="employee-table-title">Trainer Assignments Today</div>
                <ul class="list-no-style list-no-padding list-no-margin">
                    <?php
                    // Get trainers who have classes scheduled for today's day of the week
                    $today_day = date('l'); // Gets current day name (Monday, Tuesday, etc.)
                    $sql = "SELECT DISTINCT CONCAT(e.first_name, ' ', e.last_name) as full_name,
                                  e.position,
                                  GROUP_CONCAT(DISTINCT fc.time_slot ORDER BY FIELD(fc.time_slot, 'Morning', 'Afternoon', 'Evening')) as time_slots
                           FROM employees e
                           INNER JOIN fitness_classes fc ON e.id = fc.trainer_id
                           WHERE e.status = 'Active' 
                           AND (e.position = 'Trainer' OR e.position = 'Coach')
                           AND fc.day_of_week = ?
                           GROUP BY e.id
                           ORDER BY e.first_name, e.last_name";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $today_day);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $time_slots_text = str_replace(",", ", ", $row['time_slots']);
                            echo "<li class='list-margin-bottom-8'>";
                            echo "<strong>" . htmlspecialchars($row['position'] . " " . $row['full_name']) . "</strong><br>";
                            echo "<small style='color: #8a94a6;'>" . htmlspecialchars($time_slots_text) . "</small>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li class='list-margin-bottom-8'>No trainers scheduled for today</li>";
                    }
                    $stmt->close();
                    ?>
                </ul>
            </div>
        </div>

        <!-- Recent Payments Table -->
    <div class="card card-margin-bottom">
            <div class="employee-table-title">Recent Payments</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Member Name</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Get recent membership payments
                        $sql = "SELECT CONCAT(m.first_name, ' ', m.last_name) as full_name, ms.amount_paid, ms.created_at, ms.payment_status 
                               FROM member_subscriptions ms 
                               JOIN members m ON ms.member_id = m.id 
                               ORDER BY ms.created_at DESC 
                               LIMIT 5";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $status_class = $row['payment_status'] == 'paid' ? 'active' : 'inactive';
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['full_name']) . "</td>
                                        <td>₱" . number_format($row['amount_paid'], 2) . "</td>
                                        <td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>
                                        <td><span class='status " . $status_class . "'>" . ucfirst($row['payment_status']) . "</span></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No recent payments</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reports & Analytics Shortcut and Notifications Row -->
    <div class="flex-row flex-gap-24 flex-wrap">
            <div class="card card-flex-1 card-min-width-220 card-max-width-320 card-center-flex">
                <div class="employee-table-title">Reports & Analytics</div>
                <a href="#" class="btn btn-margin-top-16">View Detailed Reports</a>
            </div>
            <div class="card card-flex-2 card-min-width-260">
                <div class="employee-table-title">Notifications / Alerts</div>
                <ul class="list-no-style list-no-padding list-no-margin">
                    <?php
                    // Get memberships expiring in the next 7 days
                    $next_week = date('Y-m-d', strtotime('+7 days'));
                    $today = date('Y-m-d');
                    
                    $expiring_sql = "SELECT COUNT(*) as expiring_count 
                                   FROM members 
                                   WHERE membership_end_date BETWEEN '$today' AND '$next_week'
                                   AND status = 'Active'";
                    $expiring_result = $conn->query($expiring_sql);
                    $expiring_row = $expiring_result->fetch_assoc();
                    
                    if ($expiring_row['expiring_count'] > 0) {
                        echo "<li class='list-margin-bottom-8'><span style='color: #fbbf24;'>⚠</span> " . 
                             $expiring_row['expiring_count'] . " membership" . 
                             ($expiring_row['expiring_count'] > 1 ? "s" : "") . 
                             " expiring this week</li>";
                    }

                    // Get count of expired memberships
                    $expired_sql = "SELECT COUNT(*) as expired_count 
                                  FROM members 
                                  WHERE membership_end_date < CURRENT_DATE 
                                  AND status = 'Active'";
                    $expired_result = $conn->query($expired_sql);
                    $expired_row = $expired_result->fetch_assoc();
                    
                    if ($expired_row['expired_count'] > 0) {
                        echo "<li class='list-margin-bottom-8'><span style='color: #ef4444;'>⚠</span> " . 
                             $expired_row['expired_count'] . " expired membership" . 
                             ($expired_row['expired_count'] > 1 ? "s" : "") . 
                             " need attention</li>";
                    }

                    // Get pending payments count
                    $pending_sql = "SELECT COUNT(*) as pending_count 
                                  FROM member_subscriptions 
                                  WHERE payment_status = 'pending'";
                    $pending_result = $conn->query($pending_sql);
                    $pending_row = $pending_result->fetch_assoc();
                    
                    if ($pending_row['pending_count'] > 0) {
                        echo "<li class='list-margin-bottom-8'><span style='color: #fbbf24;'>💰</span> " . 
                             $pending_row['pending_count'] . " pending payment" . 
                             ($pending_row['pending_count'] > 1 ? "s" : "") . 
                             " to process</li>";
                    }

                    // If no notifications
                    if ($expiring_row['expiring_count'] == 0 && 
                        $expired_row['expired_count'] == 0 && 
                        $pending_row['pending_count'] == 0) {
                        echo "<li class='list-margin-bottom-8'><span style='color: #22c55e;'>✓</span> No urgent notifications</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>