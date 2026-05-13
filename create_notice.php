<?php
require_once "includes/functions.php";

require_login();

if($_SESSION['role'] == 'student'){
    die("Access denied");
}

$role = $_SESSION['role'];
$uid = $_SESSION['user_id'];

$msg = "";

/* SUBJECTS */
if($role == 'faculty'){
    $subjects = get_subjects_by_faculty($uid);
}
else{
    $subjects = $conn->query("SELECT * FROM subjects");
}

/* STUDENTS */
$students = $conn->query("
    SELECT id, name
    FROM users
    WHERE role='student'
    ORDER BY name
");

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    $subject_id = !empty($_POST['subject_id'])
        ? $_POST['subject_id']
        : NULL;

    $student_ids = $_POST['students'] ?? [];

    if(empty($title) || empty($message)){
        $msg = "Please fill all required fields.";
    }
    elseif(empty($student_ids)){
        $msg = "Please select at least one student.";
    }
    else{

        create_notice(
            $title,
            $message,
            $subject_id,
            $student_ids
        );

        $msg = "Notice uploaded successfully.";
    }
}
?>

<!doctype html>
<html>
<head>

<meta charset="utf-8">

<title>Create Notice</title>

<link rel="stylesheet" href="assets/css/style.css">

<style>

.student-box{
    max-height:300px;
    overflow-y:auto;
    border:1px solid #ddd;
    padding:15px;
    border-radius:10px;
    margin-top:10px;
    background:#fafafa;
}

.student-item{
    margin-bottom:10px;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.page-header h2{
    margin:0;
}

</style>

</head>

<body>

<div class="container">

<div class="page-header">

<h2>Create Notice</h2>

<a class="btn" href="dashboard.php">
← Back to Dashboard
</a>

</div>

<?php if($msg): ?>

<div class="alert success">
<?=$msg?>
</div>

<?php endif; ?>

<div class="card">

<form method="POST">

<label>Notice Title</label>

<input
type="text"
name="title"
required
>

<label>Notice Message</label>

<textarea
name="message"
required
style="width:100%;height:140px;"
></textarea>

<label>Select Subject</label>

<select name="subject_id">

<option value="">
General Notice
</option>

<?php foreach($subjects as $s): ?>

<option value="<?=$s['id']?>">

<?=htmlspecialchars($s['name'])?>

</option>

<?php endforeach; ?>

</select>

<br><br>

<label style="font-weight:bold;">

<input type="checkbox" id="selectAll">

Select All Students

</label>

<div class="student-box">

<?php while($stu = $students->fetch_assoc()): ?>

<div class="student-item">

<label>

<input
type="checkbox"
name="students[]"
value="<?=$stu['id']?>"
class="student-checkbox"
>

<?=htmlspecialchars($stu['name'])?>

</label>

</div>

<?php endwhile; ?>

</div>

<br>

<button class="btn">
Upload Notice
</button>

</form>

</div>

</div>

<script>

document.getElementById('selectAll')
.addEventListener('change', function(){

    const checked = this.checked;

    document
    .querySelectorAll('.student-checkbox')
    .forEach(cb => {
        cb.checked = checked;
    });
});

</script>

</body>
</html>