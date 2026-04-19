<?php
require_once "includes/functions.php";
require_role('faculty');
$fid = $_SESSION['user_id'];
$subjects = get_subjects_by_faculty($fid);
$msg='';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_id']) && isset($_POST['marks'])) {
    $subject_id = (int)$_POST['subject_id'];
    foreach ($_POST['marks'] as $student_id => $marksArr) {
        $i1 = ($marksArr['internal1'] === '') ? NULL : (int)$marksArr['internal1'];
        $i2 = ($marksArr['internal2'] === '') ? NULL : (int)$marksArr['internal2'];
        $i3 = ($marksArr['internal3'] === '') ? NULL : (int)$marksArr['internal3'];
        upsert_marks($student_id, $subject_id, $i1, $i2, $i3);
    }
    $msg = "Marks saved/updated.";
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Marks Module</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">

    <!-- Back to Dashboard Button -->
    <a class="btn" href="dashboard.php" style="margin-bottom:15px; display:inline-block;">← Back to Dashboard</a>

    <h3>Marks Module</h3>
    <?php if($msg): ?><div class="alert success"><?=$msg?></div><?php endif; ?>

    <form method="POST">
      <label>Select Subject</label>
      <select id="subjMark" name="subject_id" required>
        <option value="">-- choose --</option>
        <?php foreach($subjects as $s): ?>
          <option value="<?=$s['id']?>"><?=htmlspecialchars($s['name'])?></option>
        <?php endforeach;?>
      </select>

      <div id="marksArea"></div>

      <button class="btn" type="submit">Save Marks</button>
    </form>
  </div>

<script>
document.getElementById('subjMark').addEventListener('change', function(){
  const sid = this.value;
  const area = document.getElementById('marksArea');
  area.innerHTML = '';
  if(!sid) return;
  fetch('marks_ajax_get_students.php?subject_id='+sid)
    .then(r=>r.json())
    .then(data=>{
      if(!data.length) { area.innerHTML = '<p>No students.</p>'; return; }
      let html = '<table class="table"><tr><th>Student</th><th>CA1</th><th>CA2</th><th>CA3</th></tr>';
      data.forEach(s=>{
        html += `<tr>
                  <td>${s.name}</td>
                  <td><input type="number" min="0" max="100" name="marks[${s.id}][internal1]" value="${s.i1 ?? ''}"></td>
                  <td><input type="number" min="0" max="100" name="marks[${s.id}][internal2]" value="${s.i2 ?? ''}"></td>
                  <td><input type="number" min="0" max="100" name="marks[${s.id}][internal3]" value="${s.i3 ?? ''}"></td>
                </tr>`;
      });
      html += '</table>';
      area.innerHTML = html;
    });
});
</script>
</body>
</html>