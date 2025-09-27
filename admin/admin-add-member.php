<?php
include '../db.php';
?>
<!DOCTYPE html>
<html lang="en"                        <select id="subscription_duration" name="subscription_duration" required>
                            <option value="1" data-standard="350" data-premium="450">1 Month</option>
                            <option value="3" data-standard="960" data-premium="1250">3 Months</option>
                            <option value="12" data-standard="3800" data-premium="5100">1 Year</option>
                        </select>
                        <div id="price_display" style="margin-top: 8px; font-weight: bold; color: #dc2626;"></div>
                        <input type="hidden" name="price" id="selected_price">                       <option value="1">1 Month - ₱450</option>
                            <option value="3">3 Months - ₱1,250</option>
                            <option value="12">1 Year - ₱5,100</option><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Admin - Add Member</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        #price_display {
            margin-top: 8px;
            font-weight: bold;
            color: #dc2626;
            background: #2a2a2a;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li><a href="admin-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li><a href="admin-employees.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Employees" class="nav-icon"> Employees</a></li>
            <li><a href="admin-members.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
            <li class="active"><a href="admin-add-member.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Add Member" class="nav-icon"> Add Member</a></li>
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
            <div class="card" style="max-width: 800px; margin: 0 auto;">
                <div class="card-header">Add New Member</div>
                <form method="POST" action="add-member.php" class="member-form" autocomplete="off" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; padding: 20px;">
                    <div class="form-group" style="grid-column: 1">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                    <div class="form-group" style="grid-column: 2">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group" style="grid-column: 1">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group" style="grid-column: 2">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" required>
                    </div>
                    <div class="form-group" style="grid-column: 1">
                        <label for="membershipType">Membership Type</label>
                        <select id="membershipType" name="membershipType" required>
                            <option value="Standard">Standard</option>
                            <option value="Premium">Premium</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 2">
                        <label for="subscription_duration">Subscription Duration</label>
                        <select id="subscription_duration" name="subscription_duration" required>
                            <option value="1">1 Month - ₱450</option>
                            <option value="3">3 Months - ₱3,000</option>
                            <option value="12">1 Year - ₱12,000</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1">
                        <label for="join_date">Join Date</label>
                        <input type="date" id="join_date" name="join_date" required>
                    </div>
                    <div class="form-group" style="grid-column: 2">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="error-message">' . htmlspecialchars($_GET['error']) . '</div>';
                    }
                    ?>
                    <div class="form-buttons" style="grid-column: 1/3; margin-top: 12px; display: flex; justify-content: center; gap: 16px;">
                        <button type="submit" class="btn btn-save">Save Member</button>
                        <button type="button" class="btn btn-clear">Clear Form</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
