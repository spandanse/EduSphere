<?php
require_once "includes/functions.php";
require_role('student');

$uid = $_SESSION['user_id'];
$data = attendance_prediction($uid);
?>

<!doctype html>
<html>
<head>

<meta charset="utf-8">
<title>Smart Attendance Predictor</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container">

<h3>Smart Attendance Predictor</h3>

<div class="card">

<table class="table">

<tr>
<th>Subject</th>
<th>Total Classes</th>
<th>Present</th>
<th>Current Attendance</th>
<th>Recommendation</th>
</tr>

<?php foreach($data as $d): ?>

<tr>

<td><?= htmlspecialchars($d['subject']) ?></td>

<td><?= $d['total'] ?></td>

<td><?= $d['present'] ?></td>

<td><?= $d['current_percent'] ?>%</td>

<td>

<?php if($d['current_percent'] < 75): ?>

<span style="color:red;font-weight:bold;">
⚠ You must attend the next 
<?= $d['required_classes'] ?> classes continuously
to reach 75%.
</span>

<?php else: ?>

<span style="color:green;font-weight:bold;">
You are safe.
</span>

<br><br>

<span style="color:#444;">
You can miss only 
<b><?= $d['allowed_absent'] ?></b> more classes
before falling below 75%.
</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<a class="btn" href="dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>