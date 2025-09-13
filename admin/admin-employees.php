<?php
include '../db.php';

// Pagination settings
$rows_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;

// Get total count of employees
$count_sql = "SELECT COUNT(*) as total FROM employees";
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $rows_per_page);

// Get employees with pagination
$sql = "SELECT * FROM employees LIMIT $rows_per_page OFFSET $offset";
$result = $conn->query($sql);

// Inline edit logic
$edit_id = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : null;
$edit_row = null;
if ($edit_id) {
    $edit_result = $conn->query("SELECT * FROM employees WHERE id=$edit_id LIMIT 1");
    $edit_row = $edit_result ? $edit_result->fetch_assoc() : null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Admin - Employees</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li><a href="admin-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li class="active"><a href="admin-employees.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Employees" class="nav-icon"> Employees</a></li>
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
            <div class="user-profile">
                <img src="../images/profile pictures/default-profile.svg" alt="User">
                <span>Admin</span>
            </div>
        </div>
        <div class="members-container">
            <!-- Left Card: Add/Edit Employee Form -->
            <div class="member-form-card">
                <div class="card-header"><?php echo $edit_id ? 'Edit Employee' : 'Add New Employee'; ?></div>
                <form method="POST" action="<?php echo $edit_id ? 'edit-employee.php' : 'add-employee.php'; ?>" class="member-form" autocomplete="off">
                    <?php if ($edit_id): ?>
                        <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="empFullName">Full Name</label>
                        <input type="text" id="empFullName" name="empFullName" value="<?php echo $edit_row ? ($edit_row['full_name']) : ''; ?>" required>
                    </div>
                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div style="color: red; margin-bottom: 10px;">' . ($_GET['error']) . '</div>';
                    }
                    ?>
                    <div class="form-group">
                        <label for="empEmail">Email</label>
                        <input type="email" id="empEmail" name="empEmail" value="<?php echo $edit_row ? ($edit_row['email']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="empPassword">Password</label>
                        <input type="password" id="empPassword" name="empPassword" <?php echo $edit_id ? '' : 'required'; ?>>
                    </div>
                    <div class="form-group">
                        <label for="empPhone">Phone Number</label>
                        <input type="text" id="empPhone" name="empPhone" value="<?php echo $edit_row ? ($edit_row['phone']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="empPosition">Position</label>
                        <input type="text" id="empPosition" name="empPosition" value="<?php echo $edit_row ? ($edit_row['position']) : ''; ?>" placeholder="e.g., Trainer, Front Desk, Cleaner" required>
                    </div>
                    <div class="form-group">
                        <label for="empDateHired">Date Hired</label>
                        <input type="date" id="empDateHired" name="empDateHired" value="<?php echo $edit_row ? ($edit_row['date_hired']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="empStatus">Status</label>
                        <select id="empStatus" name="empStatus" required>
                            <option value="Active" <?php echo ($edit_row && $edit_row['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo ($edit_row && $edit_row['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-save"><?php echo $edit_id ? 'Update Employee' : 'Save Employee'; ?></button>
                        <button type="button" class="btn btn-clear">Clear Form</button>
                    </div>
                </form>
            </div>

            <!-- Right Card: Employees List Table -->
            <div class="member-table-card">
                <div class="card-header">Employees List</div>
                <div class="table-controls">
                    <input type="text" class="search-bar" placeholder="Search employees...">
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
                                <th>Position</th>
                                <th>Date Hired</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $statusClass = ($row["status"] == "Active") ? "status-active" : "status-inactive";
                                    echo "
                                    <tr>
                                        <td>".$row["id"]."</td>
                                        <td>".$row["full_name"]."</td>
                                        <td>".$row["email"]."</td>
                                        <td>".$row["phone"]."</td>
                                        <td>".$row["position"]."</td>
                                        <td>".$row["date_hired"]."</td>
                                        <td><span class='".$statusClass."'>".$row["status"]."</span></td>
                                        <td>
                                        <a href='admin-employees.php?edit_id=".$row['id']."' class='btn-action btn-edit' title='Edit'>
                                            <img src='../images/icons/edit-icon.svg' alt='Edit'>
                                        </a>
                                        <a href='delete_employee.php?id=".$row['id']."' class='btn-action btn-delete' title='Delete' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>
                                            <img src='../images/icons/delete-icon.svg' alt='Delete'>
                                        </a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No employees found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination-info">
                    Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $rows_per_page, $total_rows); ?> of <?php echo $total_rows; ?> employees
                </div>
                <div class="pagination">
                    <?php if ($total_pages > 1): ?>
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?php echo $current_page - 1; ?>" class="page-btn">‹ Previous</a>
                        <?php endif; ?>
                        
                        <?php
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                            <a href="?page=<?php echo $i; ?>" class="page-btn <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
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
