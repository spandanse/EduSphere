<?php
require_once "includes/functions.php";
require_role('student');
$uid = $_SESSION['user_id'];
$flags = preexam_flags($uid);
?>
<!doctype html>
<html><head>
  <meta charset="utf-8"><title>Pre-Exam Checklist</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head><body>
  <div class="container">
    <h3>Pre-Exam Checklist</h3>

    <div class="card">
      <h4>Low Attendance</h4>
      <?php if(empty($flags['low_attendance'])): ?>
        <p>No low attendance flags. Good job!</p>
      <?php else: ?>
        <ul>
          <?php foreach($flags['low_attendance'] as $l): ?>
            <li><?=htmlspecialchars($l['name'])?> — <?=htmlspecialchars($l['attendance_percent'])?>% (<?=$l['presents']?>/<?=$l['total_classes']?>)</li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="card">
      <h4>Incomplete Submissions</h4>
      <?php if(empty($flags['incomplete'])): ?>
        <p>All submissions complete.</p>
      <?php else: ?>
        <ul>
          <?php foreach($flags['incomplete'] as $s): ?>
            <li><?=htmlspecialchars($s['assignment_title'])?> — <?=htmlspecialchars($s['subject'])?> (Due: <?=htmlspecialchars($s['due_date'])?>)</li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <a class="btn" href="dashboard.php">Back to Dashboard</a>
  </div>
</body></html>
