<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $membershipType = $_POST['membershipType'];
    $join_date = $_POST['join_date'];
    $status = $_POST['status'];
    
    // Check if email already exists for other members
    $emailCheckQuery = "SELECT id FROM members WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($emailCheckQuery);
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: employee-edit-member.php?id=$id&error=Email already exists");
        exit();
    }

    // If password is provided, update it
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE members SET full_name=?, email=?, password=?, phone=?, membership_type=?, join_date=?, status=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $name, $email, $password, $phone, $membershipType, $join_date, $status, $id);
    } else {
        // If no password provided, update without changing password
        $query = "UPDATE members SET full_name=?, email=?, phone=?, membership_type=?, join_date=?, status=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $name, $email, $phone, $membershipType, $join_date, $status, $id);
    }

    if ($stmt->execute()) {
        header("Location: employee-members.php?success=Member updated successfully");
    } else {
        header("Location: employee-edit-member.php?id=$id&error=Error updating member");
    }
    exit();
} else {
    header("Location: employee-members.php");
    exit();
}
?>