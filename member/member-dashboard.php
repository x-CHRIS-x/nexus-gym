<?php
session_start();
include '../db.php';

// Get logged-in member ID
$member_id = $_SESSION['member_id'] ?? null;

// Fetch progress if logged in
$progress = null;
if ($member_id) {
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
            background: #1e2a38; /* dark blue-gray */
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
            color: #00c4ff; /* highlight for numbers */
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
                <div class="summary-number subscription-active">Active</div>
                <div class="summary-label">Membership Status</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-classes-icon.svg" class="summary-icon" alt="Classes Attended">
                <div class="summary-number">15</div>
                <div class="summary-label">Classes Attended</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-progress-icon.svg" class="summary-icon" alt="Progress">
                <div class="summary-number">72%</div>
                <div class="summary-label">Progress</div>
            </div>
            <div class="dashboard-card">
                <img src="../images/icons/dashboard-payment-icon.svg" class="summary-icon" alt="Subscription Status">
                <div class="summary-number subscription-expiry-active">Expires Sep 30, 2025</div>
                <div class="summary-label">Subscription Status</div>
            </div>
        </div>

        <!-- Your Progress -->
        <div class="card card-margin-bottom">
            <div class="employee-table-title">Your Progress</div>
            <div class="progress-columns">
                <!-- Column 1 -->
                <div class="progress-box">
                    <h3>Chest / Shoulders</h3>
                    <div class="progress-item"><span class="progress-label">Bench Press</span> <span class="progress-value"><?= $progress['bench_press'] ?? '-' ?></span></div>
                    <div class="progress-item"><span class="progress-label">Incline Press</span> <span class="progress-value"><?= $progress['incline_press'] ?? '-' ?></span></div>
                    <div class="progress-item"><span class="progress-label">Decline Press</span> <span class="progress-value"><?= $progress['decline_press'] ?? '-' ?></span></div>
                    <div class="progress-item"><span class="progress-label">Chest Fly</span> <span class="progress-value"><?= $progress['chest_fly'] ?? '-' ?></span></div>
                    <div class="progress-item"><span class="progress-label">Overhead Press</span> <span class="progress-value"><?= $progress['overhead_press'] ?? '-' ?></span></div>
                    <div class="progress-item"><span class="progress-label">Lateral Raises</span> <span class="progress-value"><?= $progress['lateral_raises'] ?? '-' ?></span></div>
                </div>
                <!-- Column 2 -->
                <div class="progress-box">
                    <h3>Back / Biceps</h3>
                    <div class="progress-item"><span class="progress-label">Deadlift</span> <span class="progress-value"><?= $progress['deadlift'] ?? '-' ?></span></div>
                    <div class="progress-item"><span class="progress-label">Lat Pulldown</span> <span class="progress-value"><?= $progress['lat_pulldown'] ?? '-' ?></span></div>
                </div>
                <!-- Column 3 -->
                <div class="progress-box">
                    <h3>Legs</h3>
                    <div class="progress-item"><span class="progress-label">Squat</span> <span class="progress-value"><?= $progress['squat'] ?? '-' ?></span></div>
                    <div class="progress-item"><span class="progress-label">Leg Press</span> <span class="progress-value"><?= $progress['leg_press'] ?? '-' ?></span></div>
                    <div class="progress-item"><span class="progress-label">Romanian Deadlift</span> <span class="progress-value"><?= $progress['romanian_deadlift'] ?? '-' ?></span></div>
                    <div class="progress-item"><span class="progress-label">RDL</span> <span class="progress-value"><?= $progress['rdl'] ?? '-' ?></span></div>
                </div>
            </div>
        </div>

        <!-- Upcoming Classes -->
        <div class="card card-margin-bottom">
            <div class="employee-table-title">Upcoming Classes</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Date/Time</th>
                            <th>Trainer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Yoga</td>
                            <td>Aug 18, 10:00 AM</td>
                            <td>Coach Gian</td>
                        </tr>
                        <tr>
                            <td>HIIT</td>
                            <td>Aug 19, 08:00 AM</td>
                            <td>Coach Maofyy</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subscription Reminder -->
        <div class="card">
            <div class="employee-table-title">Subscription Reminder</div>
            <div class="subscription-reminder-row">
                <span>Expiry date: <strong>Aug 31, 2025</strong></span>
                <button class="action-btn edit-btn">Renew</button>
            </div>
        </div>
    </div>
</body>
</html>
