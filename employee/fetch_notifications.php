<?php
include '../db.php';
session_start();
$employee_id = $_SESSION['employee_id'] ?? 1;

$sql = "
    SELECT n.id, n.message, n.created_at,
           m.full_name AS member_name,
           e.full_name AS employee_name
    FROM notifications n
    LEFT JOIN members m ON n.member_id = m.id
    LEFT JOIN employees e ON n.employee_id = e.id
    WHERE n.employee_id = ?
    ORDER BY n.created_at DESC
    LIMIT 10
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $msg = $row['message'];
    if ($msg === "hired") {
        $msg = "{$row['member_name']} hired you";
    }
    $notifications[] = [
        'id' => $row['id'],
        'message' => $msg,
        'time' => $row['created_at']
    ];
}

header('Content-Type: application/json');
echo json_encode($notifications);
