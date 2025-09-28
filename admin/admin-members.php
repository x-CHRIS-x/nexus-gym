<?php
session_start();
include '../db.php';

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'employee' && $_SESSION['role'] !== 'admin')) {
    header("Location: ../login.php");
    exit();
}

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
$sql = "SELECT id, full_name, email, phone, membership_type, status, membership_end_date FROM members ORDER BY membership_end_date ASC LIMIT $rows_per_page OFFSET $offset";
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
    <title>Nexus | Admin - Members</title>
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
            <li><a href="admin-add-member.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Add Member" class="nav-icon"> Add Member</a></li>
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
            <!-- Members List Table Only -->
            <div class="member-table-card">
                <div class="card-header">Members List</div>
                <div class="table-controls">
                    <input type="text" class="search-bar" placeholder="Search members...">
                    <select class="filter-dropdown">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Expired">Expired</option>
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="members-table">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Membership Type</th>
                                <th>Status</th>
                                <th>Expiry Date / Days Left</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $now = new DateTime();
                                    $end = new DateTime($row["membership_end_date"]);
                                    $interval = $now->diff($end);
                                    $daysUntilExpiry = $interval->invert ? 0 : $interval->days;
                                    
                                    // Calculate expiry date and days until expiry
                                    $expiry = isset($row["membership_end_date"]) ? $row["membership_end_date"] : null;
                                    $expiryDisplay = "-";
                                    if ($expiry) {
                                        $now = new DateTime();
                                        $end = new DateTime($expiry);
                                        $interval = $now->diff($end);
                                        $daysUntilExpiry = $interval->invert ? 0 : $interval->days;
                                        $expiryDisplay = $end->format("Y-m-d") . " (" . $daysUntilExpiry . " days left)";
                                    }

                                    // Update status based on expiry date
                                    $status = $daysUntilExpiry > 0 ? "Active" : "Expired";
                                    $statusClass = $status == "Active" ? "status-active" : "status-inactive";
                                    
                                    // Update status in database if it has changed
                                    if ($row["status"] != $status) {
                                        $updateSql = "UPDATE members SET status = ? WHERE id = ?";
                                        $stmt = $conn->prepare($updateSql);
                                        $stmt->bind_param("si", $status, $row["id"]);
                                        $stmt->execute();
                                        $stmt->close();
                                        $row["status"] = $status;
                                    }
                                    $renewBtnStyle = $daysUntilExpiry > 0 ? 
                                        "background:#666;cursor:not-allowed;color:#999;" : 
                                        "background:#22c55e;color:#fff;";
                                    
                                    echo "<tr>
                                    <td>".$row["full_name"]."</td>
                                    <td>".$row["email"]."</td>
                                    <td>".$row["phone"]."</td>
                                    <td>".$row["membership_type"]."</td>
                                    <td><span class='".$statusClass."'>".$row["status"]."</span></td>
                                    <td>".$expiryDisplay."</td>
                                    <td>
                                        <a href='admin-edit-member.php?id=".$row['id']."' class='btn-action btn-edit' title='Edit'>
                                            <img src='../images/icons/edit-icon.svg' alt='Edit'>
                                        </a>
                                        <a href='delete_member.php?id=".$row['id']."' class='btn-action btn-delete' title='Delete' onclick='return confirm(\"Are you sure you want to delete this member?\")'>
                                            <img src='../images/icons/delete-icon.svg' alt='Delete'>
                                        </a>
                                        " . ($daysUntilExpiry > 0 ? 
                                            "<span class='btn-action btn-renew' style='text-decoration:none;padding:6px 16px;border-radius:12px;font-weight:500;margin-left:32px;{$renewBtnStyle}' title='Cannot renew - membership still active'>Renew</span>" :
                                            "<a href='renew-membership.php?id=".$row['id']."' class='btn-action btn-renew' style='text-decoration:none;padding:6px 16px;border-radius:12px;font-weight:500;margin-left:32px;{$renewBtnStyle}'>Renew</a>"
                                        ) . "
                                    </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No members found</td></tr>";
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
