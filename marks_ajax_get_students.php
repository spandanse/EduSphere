<?php
// marks_ajax_get_students.php
require_once "includes/functions.php";
require_role('faculty');
$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;
header('Content-Type: application/json');

// get students and existing marks for the subject
$res = $conn->query("
  SELECT u.id, u.name, m.internal1 AS i1, m.internal2 AS i2, m.internal3 AS i3
  FROM users u
  LEFT JOIN marks m ON m.student_id = u.id AND m.subject_id = $subject_id
  WHERE u.role='student'
  ORDER BY u.name
");
$out = [];
if ($res) while ($r = $res->fetch_assoc()) $out[] = $r;
echo json_encode($out);
