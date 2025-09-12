<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    
    if (empty($employee_id)) {
        $error = "Employee ID is required!";
    } else {
        // Delete the employee
        $sql = "DELETE FROM employees WHERE id = '$employee_id'";
        
        if ($conn->query($sql) === TRUE) {
            if ($conn->affected_rows > 0) {
                $success = "Employee deleted successfully!";
            } else {
                $error = "Employee not found!";
            }
        } else {
            $error = "Error: " . $conn->error;
        }
    }
} else {
    $error = "Invalid request!";
}

// Redirect back to admin-employees.php with message
$redirect_url = "admin-employees.php";
if (isset($error)) {
    $redirect_url .= "?error=" . urlencode($error);
} elseif (isset($success)) {
    $redirect_url .= "?success=" . urlencode($success);
}

header("Location: $redirect_url");
exit();
?>
