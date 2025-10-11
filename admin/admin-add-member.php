<?php
require_once '../includes/session_check.php';
include '../db.php';

check_session(['admin']);

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
            <li><a href="admin-members.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
            <li class="active"><a href="admin-add-member.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Add Member" class="nav-icon"> Add Member</a></li>
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
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="form-group" style="grid-column: 2">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                    <div class="form-group" style="grid-column: 1/3">
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
                    <div class="form-group" style="grid-column: 1/3">
                        <label for="join_date">Join Date</label>
                        <input type="date" id="join_date" name="join_date" required>
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
                            <option value="1">1 Month</option>
                            <option value="3">3 Months</option>
                            <option value="12">1 Year</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1/3; text-align: center;">
                        <label>Total Price:</label>
                        <span id="totalPrice" style="font-size: 1.2em; font-weight: bold;">₱350</span>
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
    <script>
        function updatePrice() {
            const membershipType = document.getElementById('membershipType').value;
            const duration = parseInt(document.getElementById('subscription_duration').value);
            let basePrice = membershipType === 'Standard' ? 350 : 450;
            let totalPrice = basePrice;
            
            if (duration === 3) {
                totalPrice = membershipType === 'Standard' ? 960 : 1250;
            } else if (duration === 12) {
                totalPrice = membershipType === 'Standard' ? 3800 : 5100;
            }
            
            document.getElementById('totalPrice').textContent = '₱' + totalPrice.toLocaleString();
        }

        // Add event listeners
        document.getElementById('membershipType').addEventListener('change', updatePrice);
        document.getElementById('subscription_duration').addEventListener('change', updatePrice);
        
        // Initialize price on page load
        updatePrice();
        
        // Clear form button functionality
        document.querySelector('.btn-clear').addEventListener('click', function() {
            document.querySelector('.member-form').reset();
            updatePrice();
        });
    </script>
</body>
</html>
