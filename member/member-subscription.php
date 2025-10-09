<?php
session_start();
require_once '../includes/db_connection.php';
require_once '../includes/membership_functions.php';
require_once '../includes/session_check.php';
include 'check_membership.php';
include '../db.php';

check_session(['member']);

if (!isset($_SESSION['member_id'])) {
    header("Location: ../login.php");
    exit();
}

$memberId = $_SESSION['member_id'];

// Get current subscription status
$currentSubscription = getMemberSubscriptionStatus($memberId);

// Handle form submission for renewal instructions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plan'])) {
    $planType = $_POST['plan'];
    $renewalInfo = showRenewalInstructions($planType);
    $message = '
    <div class="alert alert-info">
        <h4 class="alert-heading">Renewal Instructions</h4>
        <p>' . $renewalInfo['message'] . '</p>
        <hr>
        <p class="mb-0">Selected Plan: <strong>' . ucfirst(str_replace(['month', 'annual'], ['-Month', ' Year'], $planType)) . '</strong></p>
        <p class="mb-0">Amount to Pay: <strong>₱' . number_format($renewalInfo['price'], 2) . '</strong></p>
    </div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus | Member - Subscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="member.css">
    <style>
        body {
            background-color: #0f0f0f;
            color: #ffffff;
        }
        /* Override Bootstrap's padding for sidebar navigation */
        #nexus-sidebar .nav-menu {
            padding: 0;
            margin: 0;
        }
        .main-content {
            background-color: #0f0f0f;
        }
        .subscription-info {
            background: #1a1a1a;
            border: 1px solid #333333;
        }
        .alert {
            background-color: #1a1a1a;
            border: 1px solid #333333;
            color: #ffffff;
        }
        .alert-success {
            background-color: #198754;
            border-color: #146c43;
        }
        .alert-danger {
            background-color: #dc3545;
            border-color: #b02a37;
        }
        .alert-info {
            background-color: #0d2f4d;
            border-color: #084298;
        }
        .alert hr {
            border-color: #333333;
        }
        .badge {
            font-size: 0.9em;
            padding: 0.5em 1em;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="nexus-sidebar" class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <?php
            $menu_items = getSidebarMenu($conn, $_SESSION['member_id']);
            foreach ($menu_items as $item) {
                $current_page = basename($_SERVER['PHP_SELF']);
                $active = ($current_page === basename($item['href'])) ? ' class="active"' : '';
                echo "<li{$active}><a href=\"{$item['href']}\"><img src=\"../images/icons/{$item['icon']}\" class=\"nav-icon\"> {$item['text']}</a></li>";
            }
            ?>
        </ul>
        <div class="logout-container">
            <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" class="nav-icon"> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h2>Subscription Management</h2>
            <div class="user-profile">
                <img src="../images/profile pictures/default-profile.svg" alt="User">
                <span>Member</span>
            </div>
        </div>

        <?php echo $message; ?>

        <!-- Current Subscription Status -->
        <div class="subscription-info p-4 rounded mb-4">
            <h3>Current Subscription</h3>
            <?php if ($currentSubscription): ?>
                <div class="mt-3">
                    <p><strong>Member:</strong> <?php echo htmlspecialchars($currentSubscription['member_name']); ?></p>
                    <p><strong>Status:</strong> <?php echo formatSubscriptionStatus($currentSubscription['status']); ?></p>
                    <p><strong>Member Since:</strong> <?php echo date('F d, Y', strtotime($currentSubscription['join_date'])); ?></p>
                    <p><strong>Membership Type:</strong> <?php echo htmlspecialchars($currentSubscription['membership_type']); ?></p>
                    <p><strong>End Date:</strong> <?php echo date('F d, Y', strtotime($currentSubscription['membership_end_date'])); ?></p>
                    <?php if ($currentSubscription['status'] === 'active'): ?>
                        <p><strong>Days Remaining:</strong> <?php echo $currentSubscription['days_remaining']; ?> days</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p class="mt-3">No subscription information found. Please renew your membership to continue.</p>
            <?php endif; ?>
        </div>

        <!-- Plans -->
        <div class="plans-container">
            <div class="plan-box">
                <div class="plan-title">Monthly Plan</div>
                <div class="plan-price">₱450</div>
                <div class="plan-desc">Stay fit with flexibility. Perfect for short-term goals.</div>
                <form method="POST" action="">
                    <input type="hidden" name="plan" value="monthly">
                    <button type="submit" class="subscribe-btn">Select Plan</button>
                </form>
            </div>
            <div class="plan-box plan-box-orange">
                <div class="plan-title">3-Month Plan</div>
                <div class="plan-price">₱1,250</div>
                <div class="plan-desc">Commit to progress. Save more with this package.</div>
                <form method="POST" action="">
                    <input type="hidden" name="plan" value="3month">
                    <button type="submit" class="subscribe-btn">Select Plan</button>
                </form>
            </div>
            <div class="plan-box plan-box-green">
                <div class="plan-title">1-Year Plan</div>
                <div class="plan-price">₱5,100</div>
                <div class="plan-desc">Go all in! Best value for your long-term fitness journey.</div>
                <form method="POST" action="">
                    <input type="hidden" name="plan" value="annual">
                    <button type="submit" class="subscribe-btn">Select Plan</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
