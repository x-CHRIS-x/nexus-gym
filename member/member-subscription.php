<?php
session_start();
require_once '../includes/db_connection.php';
require_once '../includes/membership_functions.php';

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
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li><a href="member-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li><a href="member-classes.php"><img src="../images/icons/dashboard-classes-icon.svg" alt="Classes" class="nav-icon"> Classes</a></li>
            <li><a href="member-my-plan.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" alt="My Plan" class="nav-icon"> My Plan</a></li>
            <li><a href="member-progress.php"><img src="../images/icons/dashboard-progress-icon.svg" alt="Progress" class="nav-icon"> Progress</a></li>
            <li class="active"><a href="member-subscription.php"><img src="../images/icons/dashboard-payment-icon.svg" alt="Subscription" class="nav-icon"> Subscription</a></li>
            <li><a href="member-profile.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Profile" class="nav-icon"> Profile</a></li>
        </ul>
        <div class="logout-container">
            <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
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
        <div class="subscription-info">
            <h3>Current Subscription</h3>
            <?php if ($currentSubscription): ?>
                <p><strong>Status:</strong> <?php echo formatSubscriptionStatus($currentSubscription['status']); ?></p>
            <?php else: ?>
                <p>No active subscription found. Please renew your membership to continue.</p>
            <?php endif; ?>
        </div>

        <!-- Plans -->
        <div class="plans-container">
            <div class="plan-box">
                <div class="plan-title">Monthly Plan</div>
                <div class="plan-price">₱1200</div>
                <div class="plan-desc">Stay fit with flexibility. Perfect for short-term goals.</div>
                <form method="POST" action="">
                    <input type="hidden" name="plan" value="monthly">
                    <button type="submit" class="subscribe-btn">Select Plan</button>
                </form>
            </div>
            <div class="plan-box plan-box-orange">
                <div class="plan-title">3-Month Plan</div>
                <div class="plan-price">₱3000</div>
                <div class="plan-desc">Commit to progress. Save more with this package.</div>
                <form method="POST" action="">
                    <input type="hidden" name="plan" value="3month">
                    <button type="submit" class="subscribe-btn">Select Plan</button>
                </form>
            </div>
            <div class="plan-box plan-box-green">
                <div class="plan-title">1-Year Plan</div>
                <div class="plan-price">₱12000</div>
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
