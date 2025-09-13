<?php
session_start();
include 'db.php'; // your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role     = $_POST['role']; // admin, member, employee
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    if ($role === "admin") {
        // Admin login from DB
        $sql = "SELECT * FROM admins WHERE username='$username' LIMIT 1";
    } elseif ($role === "member") {
        $sql = "SELECT * FROM members WHERE email='$username' LIMIT 1";
    } elseif ($role === "employee") {
        $sql = "SELECT * FROM employees WHERE email='$username' LIMIT 1";
    } else {
        die("Invalid role");
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Check hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role']    = $role;

            if ($role === "admin") {
                header("Location: admin/admin-dashboard.php");
            } elseif ($role === "member") {
                header("Location: member/member-dashboard.php");
            } else {
                header("Location: employee/employee-dashboard.php");
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid credentials!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Invalid credentials!";
        header("Location: login.php");
        exit();
    }
}
?>
