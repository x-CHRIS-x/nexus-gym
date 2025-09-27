<?php
require_once '../includes/db_connection.php';

// Update active members with random future dates for testing
$sql = "UPDATE members SET membership_end_date = 
        CASE 
            WHEN status = 'Active' THEN DATE_ADD(CURDATE(), INTERVAL FLOOR(RAND() * 365) DAY)
            ELSE NULL 
        END
        WHERE membership_end_date IS NULL";

if ($conn->query($sql)) {
    echo "Membership end dates updated successfully!";
} else {
    echo "Error updating membership end dates: " . $conn->error;
}

$conn->close();
?>