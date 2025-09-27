<?php
include '../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Admin - Add Member</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li><a href="admin-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li><a href="admin-employees.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Employees" class="nav-icon"> Employees</a></li>
            <li class="active"><a href="admin-members.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
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
            <div class="user-profile">
                <img src="../images/profile pictures/default-profile.svg" alt="User">
                <span>Admin</span>
            </div>
        </div>
        <div class="members-container">
            <!-- Add Member Form Card Only -->
            <div class="member-form-card">
                <div class="card-header">Add New Member</div>
                <form method="POST" action="add-member.php" class="member-form" autocomplete="off">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div style="color: red; margin-bottom: 10px;">' . ($_GET['error']) . '</div>';
                    }
                    ?>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" id="password" name="password" placeholder="e.g., member123" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="membershipType">Membership Type</label>
                        <select id="membershipType" name="membershipType" required>
                            <option value="Standard">Standard</option>
                            <option value="Premium">Premium</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="join_date">Join Date</label>
                        <input type="date" id="join_date" name="join_date" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-save">Save Member</button>
                        <button type="button" class="btn btn-clear">Clear Form</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
