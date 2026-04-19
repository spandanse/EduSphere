<?php
require_once "includes/functions.php";
require_role('faculty');
$fid = $_SESSION['user_id'];
$subjects = get_subjects_by_faculty($fid);
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_id']) && isset($_POST['attendance'])) {
    $subject_id = (int)$_POST['subject_id'];
    $attendance = $_POST['attendance']; // associative student_id => 'present'/'absent'
    record_attendance_bulk($subject_id, $attendance, $_POST['date'] ?? null);
    $msg = "Attendance recorded for selected students.";
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage Attendance</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">

    <!-- Back to Dashboard Button -->
    <a class="btn" href="dashboard.php" style="margin-bottom:15px; display:inline-block;">← Back to Dashboard</a>

    <h3>Attendance Module</h3>
    <?php if($msg): ?><div class="alert success"><?=$msg?></div><?php endif; ?>

    <form method="POST">
      <label>Select Subject</label>
      <select id="subjectSelect" name="subject_id" required>
        <option value="">-- choose --</option>
        <?php foreach($subjects as $s): ?>
          <option value="<?=$s['id']?>"><?=htmlspecialchars($s['name'])?> (ID: <?=$s['id']?>)</option>
        <?php endforeach; ?>
      </select>

      <label>Date</label>
      <input type="date" name="date" value="<?=date('Y-m-d')?>" required>

      <div id="studentsArea">
        <!-- populated by JS after selecting subject -->
      </div>

      <button class="btn" type="submit">Save Attendance</button>
    </form>
  </div>

<script>
document.getElementById('subjectSelect').addEventListener('change', function(){
  const sid = this.value;
  const area = document.getElementById('studentsArea');
  area.innerHTML = '';
  if(!sid) return;
  // fetch students list for the subject (server-side page)
  fetch('attendance_ajax_get_students.php?subject_id='+sid)
    .then(r=>r.json())
    .then(data=>{
      if(!data.length) { area.innerHTML = '<p>No students found.</p>'; return; }
      let html = '<table class="table"><tr><th>Student</th><th>Status</th></tr>';
      data.forEach(s=>{
        html += `<tr>
                  <td>${s.name}</td>
                  <td>
                    <select name="attendance[${s.id}]">
                      <option value="present">Present</option>
                      <option value="absent">Absent</option>
                    </select>
                  </td>
                 </tr>`;
      });
      html += '</table>';
      area.innerHTML = html;
    });
});
</script>
</body>
</html>