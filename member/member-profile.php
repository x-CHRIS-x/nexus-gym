<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Member - Profile</title>
    <link rel="stylesheet" href="member.css">
    <style>
        /* Toast Notification Styling */
        .toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #2ecc71;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease, top 0.4s ease;
            z-index: 9999;
        }
        .toast.show {
            opacity: 1;
            top: 40px;
            pointer-events: auto;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li><a href="member-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li><a href="member-classes.php"><img src="../images/icons/dashboard-classes-icon.svg" alt="Classes" class="nav-icon"> Classes</a></li>
            <li><a href="member-my-plan.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" alt="My Plan" class="nav-icon"> My Plan</a></li>
            <li><a href="member-progress.php"><img src="../images/icons/dashboard-progress-icon.svg" alt="Progress" class="nav-icon"> Progress</a></li>
            <li><a href="member-subscription.php"><img src="../images/icons/dashboard-payment-icon.svg" alt="Subscription" class="nav-icon"> Subscription</a></li>
            <li class="active"><a href="member-profile.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Profile" class="nav-icon"> Profile</a></li>
        </ul>
        <div class="logout-container">
            <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h2>Member Profile</h2>
            <div class="user-profile">
                <img src="../images/profile pictures/default-profile.svg" alt="User" id="profile-img" class="profile-img-style">
                <span>Member</span>
            </div>
        </div>

        <div class="profile-container">
            <form class="profile-form-grid" id="profileForm">
                <div class="profile-picture-section profile-picture-top">
                    <img src="../images/profile pictures/default-profile.svg" alt="Profile Picture" id="profile-picture-preview">
                    <input type="file" id="profile-picture" name="profile-picture" accept="image/*">
                </div>
                <div class="profile-form-col left-col">
                    <div class="profile-fields uneditable-fields">
                        <label>Full Name</label>
                        <input type="text" value="John Doe" readonly>

                        <label>Email Address</label>
                        <input type="email" value="johndoe@email.com" readonly>

                        <label>Date of Birth</label>
                        <input type="date" value="1990-01-01" readonly>

                        <label>Gender</label>
                        <input type="text" value="Male" readonly>

                        <label>Membership Type</label>
                        <input type="text" value="Premium" readonly>

                        <label>Join Date</label>
                        <input type="date" value="2023-01-01" readonly>
                    </div>
                </div>
                <div class="profile-form-col right-col">
                    <div class="profile-fields editable-fields">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" value="" placeholder="Enter phone number">

                        <label>Address</label>
                        <input type="text" name="address" value="" placeholder="Enter address">

                        <label>Emergency Contact</label>
                        <input type="text" name="emergency" value="" placeholder="Enter emergency contact">

                        <label>Change Password</label>
                        <input type="password" name="current-password" placeholder="Current password">
                        <input type="password" name="new-password" placeholder="New password">
                        <input type="password" name="confirm-password" placeholder="Confirm new password">
                    </div>
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="toast">✅ Changes saved! Please visit the front desk if you need a key card or locker key.</div>

    <script>
        const form = document.getElementById("profileForm");
        const toast = document.getElementById("toast");

        form.addEventListener("submit", function(e) {
            e.preventDefault(); // prevent normal submit
            showToast();
        });

        function showToast() {
            toast.classList.add("show");
            setTimeout(() => {
                toast.classList.remove("show");
            }, 4000); // hide after 4s
        }
    </script>
</body>
</html>
