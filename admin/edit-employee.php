<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $name = ($_POST['empFullName']);
    $email = ($_POST['empEmail']);
    $phone = ($_POST['empPhone']);
    $position = ($_POST['empPosition']);
    $date_hired = ($_POST['empDateHired']);
    $status = ($_POST['empStatus']);
    $password = $_POST['empPassword'];

    // Only update password if provided
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE employees SET full_name='$name', email='$email', phone='$phone', position='$position', date_hired='$date_hired', status='$status', password='$hashed' WHERE id=$id";
    } else {
        $sql = "UPDATE employees SET full_name='$name', email='$email', phone='$phone', position='$position', date_hired='$date_hired', status='$status' WHERE id=$id";
    }
    $conn->query($sql);
    header("Location: admin-employees.php");
    exit();
} else {
    header("Location: admin-employees.php");
    exit();
}
?>