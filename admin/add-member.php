<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $membership_type = $_POST['membershipType'];
    $join_date = $_POST['startDate'];
    $status = $_POST['status'];
    
    // Basic validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($membership_type) || empty($join_date) || empty($status)) {
        $error = "All fields are required!";
    } else {
        // Check if email already exists
        $check_email = "SELECT id FROM members WHERE email = '$email'";
        $result = $conn->query($check_email);
        
        if ($result->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            // Insert new member
            $sql = "INSERT INTO members (full_name, email, phone, membership_type, join_date, status) 
                    VALUES ('$full_name', '$email', '$phone', '$membership_type', '$join_date', '$status')";
            
            if ($conn->query($sql) === TRUE) {
                $success = "Member added successfully!";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}

// Redirect back to admin-members.php with message
$redirect_url = "admin-members.php";
if (isset($error)) {
    $redirect_url .= "?error=" . urlencode($error);
} elseif (isset($success)) {
    $redirect_url .= "?success=" . urlencode($success);
}

header("Location: $redirect_url");
exit();
?>
