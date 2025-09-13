<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['fullName']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $membershipType = $conn->real_escape_string($_POST['membershipType']);
    $startDate = $conn->real_escape_string($_POST['startDate']);
    $status = $conn->real_escape_string($_POST['status']);
    $password = $_POST['password'];

    // Only update password if provided
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE members SET full_name='$name', email='$email', phone='$phone', membership_type='$membershipType', join_date='$startDate', status='$status', password='$hashed' WHERE id=$id";
    } else {
        $sql = "UPDATE members SET full_name='$name', email='$email', phone='$phone', membership_type='$membershipType', join_date='$startDate', status='$status' WHERE id=$id";
    }
    $conn->query($sql);
    header("Location: admin-members.php");
    exit();
} else {
    header("Location: admin-members.php");
    exit();
}
?>