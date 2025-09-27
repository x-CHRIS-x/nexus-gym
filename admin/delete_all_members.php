<?php
include '../db.php';

// Start transaction
$conn->begin_transaction();

try {
    // First delete all training sessions
    $sql = "DELETE FROM training_sessions";
    $conn->query($sql);
    
    // Then delete all members
    $sql = "DELETE FROM members";
    $conn->query($sql);
    
    $conn->commit();
    echo "All members and their related records have been deleted successfully!";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();

echo "<br><br><a href='admin-members.php'>Back to Members List</a>";
?>