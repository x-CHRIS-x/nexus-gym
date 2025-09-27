<?php
include '../db.php';

// Check if member ID is provided
if (!isset($_GET['id'])) {
    header("Location: employee-members.php");
    exit();
}

// Fetch member details
$memberId = $_GET['id'];
$query = "SELECT * FROM members WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $memberId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: employee-members.php?error=Member not found");
    exit();
}

$member = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Employee - Edit Member</title>
    <link rel="stylesheet" href="employee.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li><a href="employee-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li class="active"><a href="employee-members.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
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
            <div class="user-profile">
                <img src="../images/profile pictures/default-profile.svg" alt="User">
                <span>Employee</span>
            </div>
        </div>
        <div class="members-container">
            <div class="card" style="max-width: 800px; margin: 0 auto;">
                <div class="card-header">Edit Member</div>
                <form method="POST" action="edit_member.php" class="member-form" autocomplete="off" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; padding: 20px;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($member['id']); ?>">
                    <div class="form-group" style="grid-column: 1">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($member['full_name']); ?>" required>
                    </div>
                    <div class="form-group" style="grid-column: 2">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>
                    </div>
                    <div class="form-group" style="grid-column: 1">
                        <label for="password">Password (leave blank to keep current)</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <div class="form-group" style="grid-column: 2">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" required>
                    </div>
                    <div class="form-group" style="grid-column: 1">
                        <label for="membershipType">Membership Type</label>
                        <select id="membershipType" name="membershipType" required>
                            <option value="Standard" <?php echo ($member['membership_type'] == 'Standard') ? 'selected' : ''; ?>>Standard</option>
                            <option value="Premium" <?php echo ($member['membership_type'] == 'Premium') ? 'selected' : ''; ?>>Premium</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 2">
                        <label for="join_date">Join Date</label>
                        <input type="date" id="join_date" value="<?php echo htmlspecialchars($member['join_date']); ?>" readonly style="background-color: rgba(128, 128, 128, 0.1); cursor: not-allowed;">
                        <input type="hidden" name="join_date" value="<?php echo htmlspecialchars($member['join_date']); ?>">
                    </div>
                    <div class="form-group" style="grid-column: 1/3">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Active" <?php echo ($member['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo ($member['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="error-message" style="grid-column: 1/3;">' . htmlspecialchars($_GET['error']) . '</div>';
                    }
                    ?>
                    <div class="form-buttons" style="grid-column: 1/3; margin-top: 12px; display: flex; justify-content: center; gap: 16px;">
                        <button type="submit" class="btn btn-save">Update Member</button>
                        <a href="employee-members.php" class="btn btn-clear">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>