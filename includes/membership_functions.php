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
            CASE 
                WHEN membership_end_date >= CURDATE() THEN 'active'
                ELSE 'inactive'
            END as status
        FROM members 
        WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $memberId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $member = $result->fetch_assoc();
        $member['days_remaining'] = $member['status'] === 'active' ? 
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
        case 'active':
            return '<span class="badge bg-success">Active</span>';
        case 'inactive':
            return '<span class="badge bg-danger">Inactive</span>';
        default:
            return '<span class="badge bg-secondary">Unknown</span>';
    }
}
?>