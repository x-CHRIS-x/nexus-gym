<?php
session_start();
include 'db.php'; // connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role     = $_POST['role']; // member, employee, admin
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    if ($role === "admin") {
        // Hardcoded admin credentials
        $admin_username = "nexusadmin";
        $admin_password = "admin123";

        if ($username === $admin_username && $password === $admin_password) {
            $_SESSION['user_id'] = 1;
            $_SESSION['role']    = "admin";
            header("Location: admin/admin-dashboard.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Incorrect admin username or password!";
            header("Location: login.php");
            exit();
        }
    } else {
        // Member or Employee login from DB
        if ($role === "member") {
            $sql = "SELECT * FROM members WHERE email='$username' LIMIT 1";
        } elseif ($role === "employee") {
            $sql = "SELECT * FROM employee WHERE email='$username' LIMIT 1";
        } else {
            die("Invalid role");
        }

        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Check password
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role']    = $role;

                if ($role === "member") {
                    header("Location: member/member-dashboard.php");
                } else {
                    header("Location: employee/employee-dashboard.php");
                }
                exit();
            } else {
                $_SESSION['login_error'] = "Incorrect password!";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['login_error'] = "Don't have account";
            header("Location: login.php");
            exit();
        }
    }
}
?>
