<?php
require_once "includes/functions.php";
require_login();

header('Content-Type: application/json');

if (!isset($_GET['student_id'])) {
    echo json_encode(["error" => "Missing student_id"]);
    exit;
}

$student_id = (int)$_GET['student_id'];
$faculty_id = $_SESSION['user_id'];

global $conn;

$stmt = $conn->prepare("
    SELECT 
        a.date,
        a.status
    FROM attendance a
    JOIN subjects s ON a.subject_id = s.id
    WHERE a.student_id = ?
      AND s.faculty_id = ?
    ORDER BY a.date DESC
");

if (!$stmt) {
    echo json_encode(["error" => $conn->error]);
    exit;
}

$stmt->bind_param("ii", $student_id, $faculty_id);
$stmt->execute();

$res = $stmt->get_result();

$data = [];

while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);