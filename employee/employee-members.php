<?php
require_once '../includes/session_check.php';
include '../db.php';

check_session(['employee']);

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
$sql = "SELECT id, first_name, last_name, email, phone, membership_type, status, membership_end_date FROM members ORDER BY first_name ASC, last_name ASC LIMIT $rows_per_page OFFSET $offset";
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
                    <input type="text" id="searchInput" class="search-bar" placeholder="Search members...">
                    <button id="searchBtn" class="search-btn" style="padding: 8px 16px; background: #00c4ff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 0 10px;">
                        Search
                    </button>
                    <select id="statusFilter" class="filter-dropdown">
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
                                    // Show renew button if expired or within 3 days of expiry
                                    $renewBtnStyle = ($daysUntilExpiry > 3) ? 
                                        "background:#666;cursor:not-allowed;color:#999;" : 
                                        "background:#22c55e;color:#fff;";
                                    
                                    echo "<tr>
                                    <td>".$row["first_name"]." ".$row["last_name"]."</td>
                                    <td>".$row["email"]."</td>
                                    <td>".$row["phone"]."</td>
                                    <td>".$row["membership_type"]."</td>
                                    <td><span class='".$statusClass."'>".$row["status"]."</span></td>
                                    <td>".$expiryDisplay."</td>
                                    <td>
                                        <a href='employee-edit-member.php?id=".$row['id']."' class='btn-action btn-edit' title='Edit'>
                                            <img src='../images/icons/edit-icon.svg' alt='Edit'>
                                        </a>
                                        <a href='delete_member.php?id=".$row['id']."' class='btn-action btn-delete' title='Delete' onclick='return confirm(\"Are you sure you want to delete this member?\")'>
                                            <img src='../images/icons/delete-icon.svg' alt='Delete'>
                                        </a>
                                        " . ($daysUntilExpiry > 3 ? 
                                            "<span class='btn-action btn-renew' style='text-decoration:none;padding:6px 16px;border-radius:12px;font-weight:500;margin-left:32px;{$renewBtnStyle}' title='Can only renew within 3 days of expiry'>Renew</span>" :
                                            "<a href='renew-membership.php?id=".$row['id']."' class='btn-action btn-renew' style='text-decoration:none;padding:6px 16px;border-radius:12px;font-weight:500;margin-left:32px;{$renewBtnStyle}'>Renew</a>"
                                        ) . "
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
    <script>
        // Prevent back button after logout
        window.onload = function() {
            if(typeof history.pushState === "function") {
                history.pushState("jibberish", null, null);
                window.onpopstate = function () {
                    history.pushState('newjibberish', null, null);
                };
            }
        }
        
        // Handle when the page is accessed after logout
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.cookie.includes('PHPSESSID')) {
                window.location.replace('../login.php');
            }
        });

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const statusFilter = document.getElementById('statusFilter');
            const table = document.querySelector('.members-table');
            const tableRows = table.getElementsByTagName('tr');

            // Function to perform the search and filter
            function searchAndFilter() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusTerm = statusFilter.value;

                // Start from 1 to skip header row
                for (let i = 1; i < tableRows.length; i++) {
                    const row = tableRows[i];
                    if (row.cells) { // Check if it's a valid row with cells
                        const name = row.cells[0].textContent.toLowerCase();
                        const email = row.cells[1].textContent.toLowerCase();
                        const phone = row.cells[2].textContent.toLowerCase();
                        const membershipType = row.cells[3].textContent.toLowerCase();
                        const status = row.cells[4].textContent.toLowerCase();

                        // Check if row matches both search term and status filter
                        const matchesSearch = searchTerm === '' || 
                            name.includes(searchTerm) || 
                            email.includes(searchTerm) || 
                            phone.includes(searchTerm) ||
                            membershipType.includes(searchTerm);

                        const matchesStatus = statusTerm === '' || status.includes(statusTerm.toLowerCase());

                        row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                    }
                }
            }

            // Search button click event
            searchBtn.addEventListener('click', searchAndFilter);

            // Search on Enter key
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchAndFilter();
                }
            });

            // Status filter change event
            statusFilter.addEventListener('change', searchAndFilter);

            // Add hover effect to search button
            searchBtn.addEventListener('mouseover', function() {
                this.style.background = '#0099ff';
            });
            searchBtn.addEventListener('mouseout', function() {
                this.style.background = '#00c4ff';
            });

            // Add hover effect to search button active state
            searchBtn.addEventListener('mousedown', function() {
                this.style.background = '#0088ee';
            });
            searchBtn.addEventListener('mouseup', function() {
                this.style.background = '#00c4ff';
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>
