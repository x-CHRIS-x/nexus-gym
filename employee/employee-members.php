<?php
include '../db.php';

// Pagination settings
$rows_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;

// Get total count of members
$count_sql = "SELECT COUNT(*) as total FROM members";
$count_result = $conn->query($count_sql);

if (!$count_result) {
    die("SQL Error in count query: " . $conn->error);
}

$total_rows = (int)$count_result->fetch_assoc()['total'];
$total_pages = ($rows_per_page > 0) ? ceil($total_rows / $rows_per_page) : 1;

// Check if membership_end_date column exists
$check_col_sql = "
    SELECT COUNT(*) as cnt
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'members'
      AND COLUMN_NAME = 'membership_end_date'
";
$col_res = $conn->query($check_col_sql);
if (!$col_res) {
    die("SQL Error checking columns: " . $conn->error);
}
$has_end_date = (bool)$col_res->fetch_assoc()['cnt'];

// Build select list dynamically so we don't fail if column is missing
$select_list = "id, full_name, email, phone, membership_type, status";
if ($has_end_date) {
    $select_list .= ", membership_end_date";
    $order_by = "membership_end_date ASC";
} else {
    // fallback: return a NULL column so the rest of the code can read it safely
    $select_list .= ", NULL AS membership_end_date";
    $order_by = "id ASC";
}

// Get members with pagination
$sql = "SELECT $select_list FROM members ORDER BY $order_by LIMIT $rows_per_page OFFSET $offset";
$result = $conn->query($sql);

if (!$result) {
    die("SQL Error in members query: " . $conn->error);
}

// Inline edit logic
$edit_id = isset($_GET['edit_id']) ? intval($_GET['edit_id']) : null;
$edit_row = null;
if ($edit_id) {
    $edit_result = $conn->query("SELECT * FROM members WHERE id=" . $edit_id . " LIMIT 1");
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
            <!-- Members List Table Only -->
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
                                    $statusClass = ($row["status"] == "Active") ? "status-active" : "status-inactive";

                                    // Prepare expiry info safely:
                                    $expiry = isset($row["membership_end_date"]) ? $row["membership_end_date"] : null;
                                    $expiryDisplay = "-";
                                    $daysUntilExpiry = 0;

                                    // treat NULL or empty or '0000-00-00' as no expiry
                                    if (!empty($expiry) && $expiry !== '0000-00-00') {
                                        try {
                                            $now = new DateTime();
                                            $end = new DateTime($expiry);
                                            $interval = $now->diff($end);
                                            $daysUntilExpiry = $interval->invert ? 0 : $interval->days;
                                            $expiryDisplay = $end->format("Y-m-d") . " (" . $daysUntilExpiry . " days left)";
                                        } catch (Exception $e) {
                                            // if invalid date format, fall back to '-'
                                            $expiryDisplay = "-";
                                            $daysUntilExpiry = 0;
                                        }
                                    }

                                    $renewBtnStyle = $daysUntilExpiry > 0 ?
                                        "background:#666;cursor:not-allowed;color:#999;" :
                                        "background:#22c55e;color:#fff;";

                                    echo "<tr>
                                    <td>".htmlspecialchars($row["full_name"])."</td>
                                    <td>".htmlspecialchars($row["email"])."</td>
                                    <td>".htmlspecialchars($row["phone"])."</td>
                                    <td>".htmlspecialchars($row["membership_type"])."</td>
                                    <td><span class='".$statusClass."'>".htmlspecialchars($row["status"])."</span></td>
                                    <td>".$expiryDisplay."</td>
                                    <td>
                                        <a href='employee-edit-member.php?id=".urlencode($row['id'])."' class='btn-action btn-edit' title='Edit'>
                                            <img src='../images/icons/edit-icon.svg' alt='Edit'>
                                        </a>
                                        <a href='delete_member.php?id=".urlencode($row['id'])."' class='btn-action btn-delete' title='Delete' onclick='return confirm(\"Are you sure you want to delete this member?\")'>
                                            <img src='../images/icons/delete-icon.svg' alt='Delete'>
                                        </a>"
                                        . ($daysUntilExpiry > 0 ?
                                            "<span class='btn-action btn-renew' style='text-decoration:none;padding:6px 16px;border-radius:12px;font-weight:500;margin-left:32px;{$renewBtnStyle}' title='Cannot renew - membership still active'>Renew</span>" :
                                            "<a href='renew-membership.php?id=".urlencode($row['id'])."' class='btn-action btn-renew' style='text-decoration:none;padding:6px 16px;border-radius:12px;font-weight:500;margin-left:32px;{$renewBtnStyle}'>Renew</a>"
                                        ) .
                                    "</td>
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
                    Showing <?php echo ($total_rows > 0) ? ($offset + 1) : 0; ?> to <?php echo ($total_rows > 0) ? min($offset + $rows_per_page, $total_rows) : 0; ?> of <?php echo $total_rows; ?> members
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
