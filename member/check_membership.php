<?php
// Function to check if membership is expired
function checkMembershipStatus($conn, $member_id) {
    $stmt = $conn->prepare("SELECT membership_end_date FROM members WHERE id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $today = new DateTime();
    $end_date = new DateTime($result['membership_end_date']);
    
    return $end_date > $today;
}

// Function to check if current page is allowed for expired members
function isAllowedPage($current_page) {
    $allowed_pages = [
        'membership-expired.php',
        'member-subscription.php'
    ];
    
    return in_array($current_page, $allowed_pages);
}

// Redirect expired members if trying to access restricted pages
function handleExpiredMembership($conn, $member_id) {
    if (!checkMembershipStatus($conn, $member_id)) {
        $current_page = basename($_SERVER['PHP_SELF']);
        if (!isAllowedPage($current_page)) {
            header('Location: membership-expired.php');
            exit;
        }
    }
}

// Function to get sidebar menu based on membership status and current page
function getSidebarMenu($conn, $member_id) {
    $isActive = checkMembershipStatus($conn, $member_id);
    
    // If membership is active, show full menu
    if ($isActive) {
        return [
            ['href' => 'member-dashboard.php', 'icon' => 'dashboard-home-icon.svg', 'text' => 'Dashboard'],
            ['href' => 'member-classes.php', 'icon' => 'dashboard-classes-icon.svg', 'text' => 'Classes'],
            ['href' => 'member-progress.php', 'icon' => 'dashboard-progress-icon.svg', 'text' => 'Progress'],
            ['href' => 'member-subscription.php', 'icon' => 'dashboard-payment-icon.svg', 'text' => 'Subscription'],
            ['href' => 'member-profile.php', 'icon' => 'dashboard-profile-icon.svg', 'text' => 'Profile']
        ];
    }
    
    // For expired members, show limited menu
    return [
        ['href' => 'member-dashboard.php', 'icon' => 'dashboard-home-icon.svg', 'text' => 'Dashboard'],
        ['href' => 'member-subscription.php', 'icon' => 'dashboard-payment-icon.svg', 'text' => 'Subscription']
    ];
}