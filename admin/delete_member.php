<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $member_id = $_GET['id'];
    
    if (empty($member_id)) {
        $error = "Member ID is required!";
    } else {
        // Delete the member
        $sql = "DELETE FROM members WHERE id = '$member_id'";
        
        if ($conn->query($sql) === TRUE) {
            if ($conn->affected_rows > 0) {
                $success = "Member deleted successfully!";
            } else {
                $error = "Member not found!";
            }
        } else {
            $error = "Error: " . $conn->error;
        }
    }
} else {
    $error = "Invalid request!";
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
