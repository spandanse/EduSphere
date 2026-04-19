<?php
require_once "includes/functions.php";
require_role('student'); // or faculty if you use same page
$uid = $_SESSION['user_id'];
$flags = preexam_flags($uid);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Pre-Exam Checklist</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

  <h3>Pre-Exam Checklist</h3>

  <!-- LOW ATTENDANCE -->
  <div class="card">
    <h4>Low Attendance</h4>

    <?php if(empty($flags['low_attendance'])): ?>
      <p>No low attendance flags. Good job!</p>
    <?php else: ?>
      <ul>
        <?php foreach($flags['low_attendance'] as $l): ?>
          <li>
            <?=htmlspecialchars($l['name'])?>
            — <?=htmlspecialchars($l['attendance_percent'])?>%
            (<?=$l['presents']?>/<?=$l['total_classes']?>)
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

  <!-- LOW CA MARKS (REPLACES SUBMISSIONS) -->
  <div class="card">
    <h4>Low CA Marks</h4>

    <?php if(empty($flags['low_marks'])): ?>
      <p>All CA marks are satisfactory 👍</p>
    <?php else: ?>
      <ul>
        <?php foreach($flags['low_marks'] as $m): ?>
          <li>
            <?=htmlspecialchars($m['name'])?>
            — Avg: <?=htmlspecialchars($m['avg'])?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

  <a class="btn" href="dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>