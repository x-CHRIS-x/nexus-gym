<?php
include '../db.php';

$coach_id = $_POST['coach_id'] ?? 0;
$response = ['status' => false];

if($coach_id) {
    // Check current availability
    $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM coach_availability WHERE employee_id = ?");
    $stmt->bind_param("i", $coach_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if($res['cnt'] > 0){
        // If exists, remove availability (mark unavailable)
        $stmt = $conn->prepare("DELETE FROM coach_availability WHERE employee_id = ?");
        $stmt->bind_param("i", $coach_id);
        if($stmt->execute()) {
            $response['status'] = 'unavailable';
        }
        $stmt->close();
    } else {
        // If not exists, add availability (mark available)
        $stmt = $conn->prepare("INSERT INTO coach_availability (employee_id) VALUES (?)");
        $stmt->bind_param("i", $coach_id);
        if($stmt->execute()) {
            $response['status'] = 'available';
        }
        $stmt->close();
    }
}

echo json_encode($response);
?>
