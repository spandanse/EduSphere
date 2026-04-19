<?php
require_once "includes/functions.php";
require_login();

$uid = $_SESSION['user_id'];

if (!isset($_GET['subject'])) {
    echo json_encode([]);
    exit;
}

$subject = $_GET['subject'];

global $conn;

// 🔥 IMPORTANT: join with subjects table using NAME
$stmt = $conn->prepare("
    SELECT a.date, a.status
    FROM attendance a
    JOIN subjects s ON a.subject_id = s.id
    WHERE a.student_id = ? AND s.name = ?
    ORDER BY a.date DESC
");

$stmt->bind_param("is", $uid, $subject);
$stmt->execute();

$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);