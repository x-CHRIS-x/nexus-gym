<?php
session_start();
require_once '../includes/db_connection.php';
require_once '../includes/session_check.php';

check_session(['member']);

if (!isset($_SESSION['member_id'])) {
    header("Location: ../login.php");
    exit();
}

$memberId = $_SESSION['member_id'];

// Simple update query to set status to expired and end date to current date
$stmt = $conn->prepare("UPDATE members SET 
    status = 'expired',
    membership_end_date = CURDATE()
    WHERE id = ?");
$stmt->bind_param("i", $memberId);
$stmt->execute();

// Redirect to expired page
header("Location: membership-expired.php");
exit();
?>
?>