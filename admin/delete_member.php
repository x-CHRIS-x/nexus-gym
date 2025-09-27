<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $member_id = $_GET['id'];
    
    if (empty($member_id)) {
        $error = "Member ID is required!";
    } else {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // First delete related training sessions
            $sql = "DELETE FROM training_sessions WHERE member_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            
            // Then delete the member
            $sql = "DELETE FROM members WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $success = "Member and related records deleted successfully!";
                $conn->commit();
            } else {
                $error = "Member not found!";
                $conn->rollback();
            }
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Error: " . $e->getMessage();
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
