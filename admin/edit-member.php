<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $memberId = $_POST['memberId'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $membershipType = $_POST['membershipType'];
    $joinDate = $_POST['join_date'];
    $status = $_POST['status'];
    
    // Check if email already exists for other members
    $emailCheckQuery = "SELECT id FROM members WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($emailCheckQuery);
    $stmt->bind_param("si", $email, $memberId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: admin-edit-member.php?id=$memberId&error=Email already exists");
        exit();
    }

    // If password is provided, update it
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE members SET full_name=?, email=?, password=?, phone=?, membership_type=?, join_date=?, status=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $fullName, $email, $password, $phone, $membershipType, $joinDate, $status, $memberId);
    } else {
        // If no password provided, update without changing password
        $query = "UPDATE members SET full_name=?, email=?, phone=?, membership_type=?, join_date=?, status=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $fullName, $email, $phone, $membershipType, $joinDate, $status, $memberId);
    }

    if ($stmt->execute()) {
        header("Location: admin-members.php?success=Member updated successfully");
    } else {
        header("Location: admin-edit-member.php?id=$memberId&error=Error updating member");
    }
    exit();
} else {
    header("Location: admin-members.php");
    exit();
}
?>