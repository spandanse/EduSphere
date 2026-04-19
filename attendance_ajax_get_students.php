<?php
// attendance_ajax_get_students.php
require_once "includes/functions.php";
require_role('faculty');
$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
header('Content-Type: application/json');

// students are users with role student
$res = $conn->query("SELECT id, name FROM users WHERE role='student' ORDER BY name");
$out = [];
if ($res) {
    while ($r = $res->fetch_assoc()) $out[] = $r;
}
echo json_encode($out);