<?php
include '../db.php';

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
            <li><a href="admin-settings.php"><img src="../images/icons/dashboard-settings-icon.svg" alt="Settings" class="nav-icon"> Settings</a></li>
        </ul>
        <div class="logout-container">
            <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
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
                <img src="../images/icons/dashboard-payment-icon.svg" class="summary-icon summary-icon-38" alt="Active Subscriptions">
                <div class="summary-number">
                <?php
                    $sql = "SELECT COUNT(*) FROM members where status = 'Active'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo $row["COUNT(*)"];
                    ?>
                </div>
                <div class="summary-label">Currently Active Plans</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-progress-icon.svg" class="summary-icon summary-icon-38" alt="Revenue Overview">
                <div class="summary-number summary-number-gold">₱120,000</div>
                <div class="summary-label">Revenue (This Month)
                    </span>
                </div>
            </div>
        </div>

        <!-- Attendance Today & Trainer Assignments Row -->
    <div class="flex-row flex-gap-24 flex-mb-24 flex-wrap">
            <div class="card card-flex-1 card-min-width-220 card-max-width-300">
                <div class="employee-table-title">Attendance Today</div>
                <div class="attendance-today-number">85</div>
                <div class="attendance-today-label">Check-ins</div>
            </div>
            <div class="card card-flex-2 card-min-width-260">
                <div class="employee-table-title">Trainer Assignments Today</div>
                <ul class="list-no-style list-no-padding list-no-margin">
                    <li class="list-margin-bottom-8"><strong>marc jorem legazpi</strong> – Yoga, HIIT</li>
                    <li class="list-margin-bottom-8"><strong>na1g</strong> – Pilates, Zumba</li>
                    <li class="list-margin-bottom-8"><strong>Maofyy</strong> – Crossfit</li>
                    <li class="list-margin-bottom-8"><strong>crcrzy</strong> – Boxing</li>
                    <li class="list-margin-bottom-8"><strong>Kaishuie</strong> – Cardio</li>
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
                        <tr>
                            <td>marc jorem legazpi</td>
                            <td>₱2,000</td>
                            <td>Aug 17, 2025</td>
                            <td><span class="status active">Paid</span></td>
                        </tr>
                        <tr>
                            <td>na1g</td>
                            <td>₱1,500</td>
                            <td>Aug 17, 2025</td>
                            <td><span class="status active">Paid</span></td>
                        </tr>
                        <tr>
                            <td>Maofyy</td>
                            <td>₱2,500</td>
                            <td>Aug 16, 2025</td>
                            <td><span class="status inactive">Pending</span></td>
                        </tr>
                        <tr>
                            <td>crcrzy</td>
                            <td>₱2,000</td>
                            <td>Aug 16, 2025</td>
                            <td><span class="status active">Paid</span></td>
                        </tr>
                        <tr>
                            <td>Kaishuie</td>
                            <td>₱2,200</td>
                            <td>Aug 16, 2025</td>
                            <td><span class="status active">Paid</span></td>
                        </tr>
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
                    <li class="list-margin-bottom-8">5 memberships expiring this week</li>
                    <li class="list-margin-bottom-8">2 overdue payments</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>