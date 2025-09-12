<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = $_POST['empFullName'];
    $email = $_POST['empEmail'];
    $phone = $_POST['empPhone'];
    $role = $_POST['empRole'];
    $position = $_POST['empPosition'];
    $date_hired = $_POST['empDateHired'];
    $status = $_POST['empStatus'];
    
    // Basic validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($role) || empty($position) || empty($date_hired) || empty($status)) {
        $error = "All fields are required!";
    } else {
        // Check if email already exists
        $check_email = "SELECT id FROM employees WHERE email = '$email'";
        $result = $conn->query($check_email);
        
        if ($result->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            // Insert new employee
            $sql = "INSERT INTO employees (full_name, email, phone, role, position, date_hired, status) 
                    VALUES ('$full_name', '$email', '$phone', '$role', '$position', '$date_hired', '$status')";
            
            if ($conn->query($sql) === TRUE) {
                $success = "Employee added successfully!";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
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
