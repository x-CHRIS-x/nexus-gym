<?php
session_start();
require_once '../includes/db_connection.php';
require_once '../includes/session_check.php';

check_session(['member']);

if (!isset($_SESSION['member_id'])) {
    header("Location: ../login.php");
    exit();
}

$member_id = $_SESSION['member_id'];

// Fetch member data
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, membership_type, join_date FROM members WHERE id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $current_password = $_POST['current-password'] ?? '';
    $new_password = $_POST['new-password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';
    
    $updates = [];
    $types = "";
    $values = [];
    
    // Add first name and last name to updates
    if (!empty($first_name) && !empty($last_name)) {
        $updates[] = "first_name = ?";
        $updates[] = "last_name = ?";
        $types .= "ss";
        $values[] = $first_name;
        $values[] = $last_name;
    }
    
    // Add email to updates if changed
    if (!empty($email) && $email !== $member['email']) {
        // Check if email is already taken
        $check_stmt = $conn->prepare("SELECT id FROM members WHERE email = ? AND id != ?");
        $check_stmt->bind_param("si", $email, $member_id);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows === 0) {
            $updates[] = "email = ?";
            $types .= "s";
            $values[] = $email;
        } else {
            $error = "Email address is already in use.";
        }
        $check_stmt->close();
    }
    
    // Add phone to updates if provided
    if (!empty($phone)) {
        $updates[] = "phone = ?";
        $types .= "s";
        $values[] = $phone;
    }
    
    // Handle password change
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        if ($new_password === $confirm_password) {
            // Verify current password
            $pass_stmt = $conn->prepare("SELECT password FROM members WHERE id = ?");
            $pass_stmt->bind_param("i", $member_id);
            $pass_stmt->execute();
            $current_hash = $pass_stmt->get_result()->fetch_assoc()['password'];
            $pass_stmt->close();
            
            if (password_verify($current_password, $current_hash)) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $updates[] = "password = ?";
                $types .= "s";
                $values[] = $new_hash;
            } else {
                $error = "Current password is incorrect.";
            }
        } else {
            $error = "New passwords do not match.";
        }
    }
    
    // Perform update if there are changes and no errors
    if (!empty($updates) && empty($error)) {
        $sql = "UPDATE members SET " . implode(", ", $updates) . " WHERE id = ?";
        $types .= "i";
        $values[] = $member_id;
        
        $update_stmt = $conn->prepare($sql);
        $update_stmt->bind_param($types, ...$values);
        
        if ($update_stmt->execute()) {
            $success = "Profile updated successfully!";
            // Refresh member data
            $stmt = $conn->prepare("SELECT first_name, last_name, email, phone, membership_type, join_date FROM members WHERE id = ?");
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            $member = $stmt->get_result()->fetch_assoc();
            $stmt->close();
        } else {
            $error = "Failed to update profile.";
        }
        $update_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Member - Profile</title>
    <link rel="stylesheet" href="member.css">
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #28a745;
            color: white;
        }
        .alert-danger {
            background-color: #dc3545;
            color: white;
        }
        .editable-fields input:not([type="password"]) {
            background-color: #2d3748;
        }
        .password-section label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .password-section input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
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

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="profile-container">
            <form class="profile-form-grid" method="POST" action="">
                <div class="profile-picture-section profile-picture-top">
                    <img src="../images/profile pictures/default-profile.svg" alt="Profile Picture" id="profile-picture-preview">
                    <input type="file" id="profile-picture" name="profile-picture" accept="image/*">
                </div>
                <div class="profile-form-col left-col">
                    <div class="profile-fields editable-fields">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($member['first_name'] ?? ''); ?>" required>

                        <label>Last Name</label>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($member['last_name'] ?? ''); ?>" required>

                        <label>Email Address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($member['email'] ?? ''); ?>" required>

                        <label>Member Since</label>
                        <input type="text" value="<?php echo isset($member['join_date']) ? date('F d, Y', strtotime($member['join_date'])) : ''; ?>" readonly>

                        <label>Membership Status</label>
                        <input type="text" value="<?php echo htmlspecialchars($member['membership_type'] ?? ''); ?>" readonly>
                    </div>
                </div>
                <div class="profile-form-col right-col">
                    <div class="profile-fields editable-fields">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($member['phone'] ?? ''); ?>" placeholder="Enter phone number">

                        <div class="password-section" style="margin-top: 20px;">
                            <label>Change Password</label>
                            <input type="password" name="current-password" placeholder="Current password">
                            <br>
                            <input type="password" name="new-password" placeholder="New password" minlength="8">
                            <br>
                            <input type="password" name="confirm-password" placeholder="Confirm new password" minlength="8">
                            <small style="color: #666; display: block; margin-top: 5px;">Leave password fields empty if you don't want to change it.</small>
                        </div>
                    </div>
                    <button type="submit" class="save-btn" style="margin-top: 20px;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
