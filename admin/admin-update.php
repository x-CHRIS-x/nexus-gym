<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $conn->real_escape_string($_POST['name']);
    $email    = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Update name and email
    if (!empty($name)) {
        $conn->query("UPDATE admins SET username='$name' WHERE id=$admin_id");
    }

    if (!empty($email)) {
        $conn->query("UPDATE admins SET email='$email' WHERE id=$admin_id");
    }

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("UPDATE admins SET password='$hashed' WHERE id=$admin_id");
    }

    $_SESSION['msg'] = "Settings updated!";
    header("Location: admin-settings.php");
    exit();
}
?>