<?php
require_once 'db_connection.php';

function getMemberSubscriptionStatus($memberId) {
    global $conn;
    
    $query = "
        SELECT 
            CONCAT(first_name, ' ', last_name) as member_name,
            join_date,
            membership_end_date,
            membership_type,
            status
        FROM members 
        WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $memberId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $member = $result->fetch_assoc();
        $member['days_remaining'] = $member['status'] === 'Active' ? 
            floor((strtotime($member['membership_end_date']) - time()) / (60 * 60 * 24)) : 0;
        return $member;
    }
    
    return null;
}

function showRenewalInstructions($planType) {
    $prices = [
        'monthly' => 450,
        '3month' => 1250,
        'annual' => 5000
    ];

    $price = $prices[$planType] ?? 0;
    
    return [
        'price' => $price,
        'message' => 'Please proceed to the front desk to complete your membership renewal. ' .
                    'Our staff will assist you with the payment process and activate your membership.'
    ];
}

function formatSubscriptionStatus($status) {
    switch ($status) {
        case 'Active':
            return '<span class="badge bg-success">Active</span>';
        case 'Expired':
            return '<span class="badge bg-danger">Expired</span>';
        default:
            return '<span class="badge bg-secondary">'. htmlspecialchars($status) .'</span>';
    }
}

function cancelMembershipSubscription($memberId) {
    global $conn;
    
    // First, check if the membership is active
    $currentStatus = getMemberSubscriptionStatus($memberId);
    if (!$currentStatus || $currentStatus['status'] !== 'Active') {
        return [
            'success' => false,
            'message' => 'No active subscription found to cancel.'
        ];
    }

    // Set the membership end date to today
    $query = "UPDATE members SET 
                membership_end_date = CURDATE(),
                last_updated = NOW(),
                status = 'Expired'
              WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $memberId);
    
    if ($stmt->execute()) {
        // Log the cancellation in a new table for tracking
        $logQuery = "INSERT INTO membership_logs (member_id, action, action_date, notes) 
                    VALUES (?, 'cancellation', NOW(), 'Membership cancelled by member')";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("i", $memberId);
        $logStmt->execute();
        
        return [
            'success' => true,
            'message' => 'Your membership has been successfully cancelled.'
        ];
    } else {
        return [
            'success' => false,
            'message' => 'An error occurred while cancelling your membership. Please try again or contact support.'
        ];
    }
}
?>