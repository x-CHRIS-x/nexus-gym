<?php
include '../db.php';

$coach_id = $_POST['coach_id'] ?? 0;

$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM coach_availability WHERE employee_id=?");
$stmt->bind_param("i", $coach_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($res['cnt'] > 0) {
    $stmt = $conn->prepare("DELETE FROM coach_availability WHERE employee_id=?");
    $stmt->bind_param("i", $coach_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['status' => 'not-available']);
} else {
    $stmt = $conn->prepare("INSERT INTO coach_availability (employee_id, available_day, available_time) VALUES (?, 'Any Day', 'Any Time')");
    $stmt->bind_param("i", $coach_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['status' => 'available']);
}
