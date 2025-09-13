<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Admin Settings</title>
    <link rel="stylesheet" href="admin.css">

</head>
<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Show message if set
$msg = "";
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// Fetch current admin info
$result = $conn->query("SELECT * FROM admins WHERE id=$admin_id LIMIT 1");
$row = $result ? $result->fetch_assoc() : ['name' => '', 'email' => ''];
?>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li><a href="admin-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li><a href="admin-employees.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Employees" class="nav-icon"> Employees</a></li>
            <li><a href="admin-members.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
            <li class="active"><a href="admin-settings.php"><img src="../images/icons/dashboard-settings-icon.svg" alt="Settings" class="nav-icon"> Settings</a></li>
        </ul>
        <div class="logout-container">
            <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h2>Admin Settings</h2>
            <div class="dashboard-datetime dashboard-datetime-style" id="dashboard-datetime"></div>
            <div class="user-profile">
                <img src="../images/profile pictures/default-profile.svg" alt="User">
                <span>Admin</span>
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

        <!-- Settings Page Content -->
        <div class="settings-section">
            <div class="employee-table-title">Account Settings</div>
            <?php if ($msg) echo "<p style='color:green;'>$msg</p>"; ?>
            <form method="post" action="admin-update.php">
                <label>Admin Name</label>
                <input type="text" name="name" value="<?php echo isset($row['name']) ? htmlspecialchars($row['name']) : ''; ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo ($row['email']); ?>" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep current">

                <button type="submit" class="btn">Save Changes</button> 
            </form>
        </div>

        <div class="settings-section">
            <div class="employee-table-title">System Preferences</div>
            <label><input type="checkbox" checked> Enable notifications</label>
            <label><input type="checkbox"> Dark mode</label>
            <label><input type="checkbox" checked> Auto backup</label>
        </div>
    </div>
</body>
</html>
