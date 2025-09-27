<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $membership_type = $_POST['membershipType'];
    $join_date = $_POST['join_date'];
    $password = $_POST['password'];
    $subscription_duration = $_POST['subscription_duration'];

    // Basic validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($membership_type) || empty($join_date) || empty($password) || empty($subscription_duration)) {
        $error = "All fields are required!";
    } else {
        // Check if email already exists
        $check_email = "SELECT id FROM members WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Calculate membership end date based on subscription duration
            $end_date = date('Y-m-d', strtotime($join_date . ' + ' . $subscription_duration . ' months'));
            
            // Set status as Active for new members
            $status = 'Active';

            // Insert new member using prepared statement
            $sql = "INSERT INTO members (full_name, email, phone, membership_type, join_date, status, password, membership_end_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $full_name, $email, $phone, $membership_type, $join_date, $status, $hashedPassword, $end_date);

            if ($stmt->execute()) {
                $success = "Member added successfully!";
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Redirect back to employee-members.php with message
$redirect_url = "employee-members.php";
if (isset($error)) {
    $redirect_url .= "?error=" . urlencode($error);
} elseif (isset($success)) {
    $redirect_url .= "?success=" . urlencode($success);
}

header("Location: $redirect_url");
exit(); // connects to nexus-gym

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName       = $conn->real_escape_string($_POST['fullName']);
    $email          = $conn->real_escape_string($_POST['email']);
    $phone          = $conn->real_escape_string($_POST['phone']);
    $membershipType = $conn->real_escape_string($_POST['membershipType']);
    $status         = $conn->real_escape_string($_POST['status']);
    $join_date       = $conn->real_escape_string($_POST['join_date']); // date input from form
    
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
