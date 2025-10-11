<?php
session_start();
include '../db.php';
include 'check_membership.php';

// Check if member is logged in
if (!isset($_SESSION['member_id'])) {
    header('Location: ../login.php');
    exit;
}

// Get membership status
$member_id = $_SESSION['member_id'];
$stmt = $conn->prepare("SELECT membership_end_date FROM members WHERE id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

// If membership is not expired, redirect to dashboard
$today = new DateTime();
$end_date = new DateTime($result['membership_end_date']);
if ($end_date > $today) {
    header('Location: member-dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexus | Membership Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="member.css">
    <style>
        .expired-notice {
            background: #1e2a38;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
            color: #f5f5f5;
        }
        
        .expired-icon {
            font-size: 48px;
            color: #ff4444;
            margin-bottom: 20px;
        }
        
        .expired-message {
            font-size: 24px;
            margin-bottom: 20px;
            color: #ff4444;
        }
        
        .expired-details {
            color: #8a94a6;
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .renew-button {
            background: #00c4ff;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .renew-button:hover {
            background: #0099ff;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <?php
            $menu_items = getSidebarMenu($conn, $member_id);
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

    <div class="main-content">
        <div class="header">
            <h2>Membership Expired</h2>
            <div class="user-profile">
                <img src="../images/profile pictures/default-profile.svg" alt="User">
                <span>Member</span>
            </div>
        </div>

        <div class="expired-notice">
            <div class="expired-icon">⚠️</div>
            <div class="expired-message">Your Membership Has Expired</div>
            <div class="expired-details">
                Your membership expired on <?= date('F d, Y', strtotime($result['membership_end_date'])) ?>.<br>
                To regain access to all features and continue your fitness journey,<br>
                please renew your membership.
            </div>
            <button class="renew-button" onclick="window.location.href='member-subscription.php'">
                Renew Membership Now
            </button>
        </div>
    </div>
</body>
</html>