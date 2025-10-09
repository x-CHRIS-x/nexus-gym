<?php
include '../db.php';

header('Content-Type: application/json');

if (!isset($_GET['coach_id'])) {
    echo json_encode(['error' => 'Coach ID not provided']);
    exit;
}

$coach_id = intval($_GET['coach_id']);

$query = "SELECT 
    day_of_week as day,
    CASE 
        WHEN time_slot = 'Morning' THEN '7:00 AM - 9:00 AM'
        WHEN time_slot = 'Afternoon' THEN '2:00 PM - 4:00 PM'
        WHEN time_slot = 'Evening' THEN '6:00 PM - 8:00 PM'
    END as time,
    name as class_name
    FROM fitness_classes
    WHERE trainer_id = ?
    ORDER BY 
        FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
        FIELD(time_slot, 'Morning', 'Afternoon', 'Evening')";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $coach_id);
$stmt->execute();
$result = $stmt->get_result();

$schedule = [];
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row;
}

echo json_encode(['schedule' => $schedule]);