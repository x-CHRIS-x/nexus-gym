<?php
include '../db.php'; // connects to nexus-gym

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName       = $conn->real_escape_string($_POST['fullName']);
    $email          = $conn->real_escape_string($_POST['email']);
    $phone          = $conn->real_escape_string($_POST['phone']);
    $membershipType = $conn->real_escape_string($_POST['membershipType']);
    $status         = $conn->real_escape_string($_POST['status']);
    $joinDate       = $conn->real_escape_string($_POST['joinDate']); // date input from form
    
    // Hash password before saving
    $password       = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO members (full_name, email, password, phone, membership_type, status, join_date)
            VALUES ('$fullName', '$email', '$password', '$phone', '$membershipType', '$status', '$joinDate')";

    if ($conn->query($sql) === TRUE) {
        header("Location: employee-members.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
