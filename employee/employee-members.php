<?php
include '../db.php';

// Pagination settings
$rows_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;

// Get total count of members
$count_sql = "SELECT COUNT(*) as total FROM members";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $rows_per_page);

// Get members with pagination
$sql = "SELECT * FROM members LIMIT $rows_per_page OFFSET $offset";
$result = $conn->query($sql);

// Inline edit logic
$edit_id = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : null;
$edit_row = null;
if ($edit_id) {
    $edit_result = $conn->query("SELECT * FROM members WHERE id=$edit_id LIMIT 1");
    $edit_row = $edit_result ? $edit_result->fetch_assoc() : null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Employee - Members</title>
    <link rel="stylesheet" href="employee.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li><a href="employee-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li class="active"><a href="employee-members.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
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
            <!-- Left Card: Add/Edit Member Form -->
            <div class="member-form-card">
                <div class="card-header"><?php echo $edit_id ? 'Edit Member' : 'Add New Member'; ?></div>
                <form method="POST" action="<?php echo $edit_id ? 'edit_member.php' : 'add_member.php'; ?>" class="member-form" autocomplete="off">
                    <?php if ($edit_id): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                    <?php endif; ?>
                    <!-- Full name field-->
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" name="fullName" value="<?php echo $edit_row ? ($edit_row['full_name']) : ''; ?>" required>
                    </div>
                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div style="color: red; margin-bottom: 10px;">' . ($_GET['error']) . '</div>';
                    }
                    ?>
                    <!-- Email field-->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo $edit_row ? ($edit_row['email']) : ''; ?>" required>
                    </div>
                    <!-- Password field-->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" <?php echo $edit_id ? '' : 'required'; ?> >
                    </div>
                    <!-- Phone number field-->
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" value="<?php echo $edit_row ? ($edit_row['phone']) : ''; ?>" required>
                    </div>
                    <!-- Membership Type field-->
                    <div class="form-group">
                        <label for="membershipType">Membership Type</label>
                        <select id="membershipType" name="membershipType" required>
                            <option value="Standard" <?php echo ($edit_row && $edit_row['membership_type'] == 'Standard') ? 'selected' : ''; ?>>Standard</option>
                            <option value="Premium" <?php echo ($edit_row && $edit_row['membership_type'] == 'Premium') ? 'selected' : ''; ?>>Premium</option>
                        </select>
                    </div>
                    <!-- Start Date field-->
                    <div class="form-group">
                        <label for="startDate">Start Date</label>
                        <input type="date" id="startDate" name="startDate" value="<?php echo $edit_row && !empty($edit_row['join_date']) ? htmlspecialchars($edit_row['join_date']) : ''; ?>" required>
                    </div>
                    <!-- Status field-->
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Active" <?php echo ($edit_row && $edit_row['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo ($edit_row && $edit_row['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-save"><?php echo $edit_id ? 'Update Member' : 'Save Member'; ?></button>
                        <button type="button" class="btn btn-clear">Clear Form</button>
                    </div>
                </form>
            </div>
            <!-- Right Card: Members List Table -->
            <div class="member-table-card">
                <div class="card-header">Members List</div>
                <div class="table-controls">
                    <input type="text" class="search-bar" placeholder="Search members...">
                    <select class="filter-dropdown">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="members-table">
                        <thead>
                                <th>Phone Number</th>
                                <th>Membership Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $statusClass = ($row["status"] == "Active") ? "status-active" : "status-inactive";
                                    echo "<tr>
                                    <td>".$row["id"]."</td>
                                    <td>".$row["full_name"]."</td>
                                    <td>".$row["email"]."</td>
                                    <td>".$row["phone"]."</td>
                                    <td>".$row["membership_type"]."</td>
                                    <td><span class='".$statusClass."'>".$row["status"]."</span></td>
                                    <td>
                                        <a href='employee-members.php?edit_id=".$row['id']."' class='btn-action btn-edit' title='Edit'>
                                            <img src='../images/icons/edit-icon.svg' alt='Edit'>
                                        </a>
                                        <a href='delete_member.php?id=".$row['id']."' class='btn-action btn-delete' title='Delete' onclick='return confirm(\"Are you sure you want to delete this member?\")'>
                                            <img src='../images/icons/delete-icon.svg' alt='Delete'>
                                        </a>
                                    </td>
                                    </tr>";
                                }   
                            } else {
                                echo "<tr><td colspan='7'>No members found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination-info">
                    Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $rows_per_page, $total_rows); ?> of <?php echo $total_rows; ?> members
                </div>
                <div class="pagination">
                    <?php if ($total_pages > 1): ?>
                        <!-- Previous button -->
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?php echo $current_page - 1; ?>" class="page-btn">‹ Previous</a>
                        <?php endif; ?>
                        
                        <!-- Page numbers -->
                        <?php
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                            <a href="?page=<?php echo $i; ?>" class="page-btn <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <!-- Next button -->
                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?php echo $current_page + 1; ?>" class="page-btn">Next ›</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/pagination.js"></script>
</body>
</html>
<?php
$conn->close();
?>
