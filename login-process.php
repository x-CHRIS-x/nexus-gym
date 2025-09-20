<?php
session_start();
include 'db.php'; // your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role     = $_POST['role']; // admin, member, employee
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    if ($role === "admin") {
        $sql = "SELECT * FROM admins WHERE email='$username' LIMIT 1";
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
            // store common info
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role']    = $role;

            // store role-specific info
            if ($role === "member") {
                $_SESSION['member_id']    = $row['id'];
                $_SESSION['member_name']  = $row['full_name']; // change if column is different
                $_SESSION['member_email'] = $row['email'];
                header("Location: member/member-dashboard.php");
            } elseif ($role === "admin") {
                $_SESSION['admin_id']   = $row['id'];
                $_SESSION['admin_name'] = $row['full_name'] ?? "Admin";
                header("Location: admin/admin-dashboard.php");
            } elseif ($role === "employee") {
                $_SESSION['employee_id']   = $row['id'];
                $_SESSION['employee_name'] = $row['full_name'];
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
