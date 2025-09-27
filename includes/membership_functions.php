<?php
require_once 'db_connection.php';

function getMemberSubscriptionStatus($memberId) {
    global $conn;
    
    $query = "SELECT * FROM members WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $memberId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
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