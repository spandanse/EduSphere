<?php
require_once "includes/functions.php";

require_login();

if($_SESSION['role'] != 'student'){
    die("Access denied");
}

$uid = $_SESSION['user_id'];

/* SUBJECTS OF STUDENT */
$subjects = attendance_stats_for_student($uid);

/* SELECTED SUBJECT */
$subject_id = $_GET['subject_id'] ?? null;

$materials = [];

if($subject_id){

    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            sm.*,
            u.name AS faculty_name,
            s.name AS subject_name
        FROM study_materials sm
        JOIN users u
            ON sm.faculty_id = u.id
        JOIN subjects s
            ON sm.subject_id = s.id
        WHERE sm.subject_id = ?
        ORDER BY sm.created_at DESC
    ");

    $stmt->bind_param("i", $subject_id);
    $stmt->execute();

    $materials = $stmt->get_result();
}
?>

<!doctype html>
<html>
<head>

<meta charset="utf-8">

<title>Study Repository</title>

<link rel="stylesheet" href="assets/css/style.css">

<style>

.subject-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-top:20px;
}

.subject-card{
    background:white;
    padding:25px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:0.2s;
}

.subject-card:hover{
    transform:translateY(-4px);
}

.subject-card a{
    text-decoration:none;
    font-size:18px;
    font-weight:bold;
    color:#1a73e8;
}

.material-card{
    background:white;
    padding:18px;
    border-radius:10px;
    margin-bottom:15px;
    box-shadow:0 2px 10px rgba(0,0,0,0.06);
}

.material-title{
    font-size:18px;
    font-weight:bold;
    margin-bottom:8px;
}

.material-meta{
    color:#666;
    font-size:14px;
    margin-bottom:10px;
}

.download-btn{
    display:inline-block;
    padding:8px 14px;
    background:#1a73e8;
    color:white;
    border-radius:6px;
    text-decoration:none;
}

.download-btn:hover{
    background:#0f5fd6;
}

</style>

</head>

<body>

<div class="container">

<a href="dashboard.php" class="btn">
← Back to Dashboard
</a>

<h2 style="margin-top:20px;">
Study Repository
</h2>

<?php if(!$subject_id): ?>

<!-- SUBJECTS -->

<div class="subject-grid">

<?php foreach($subjects as $s): ?>

<div class="subject-card">

<a href="study_repository.php?subject_id=<?=$s['subject_id']?>">

<?=htmlspecialchars($s['name'])?>

</a>

</div>

<?php endforeach; ?>

</div>

<?php else: ?>

<!-- MATERIALS -->

<div style="margin-top:20px;">

<a href="study_repository.php" class="btn">
← All Subjects
</a>

</div>

<h3 style="margin-top:20px;">
Study Materials
</h3>

<?php if($materials->num_rows == 0): ?>

<div class="card">
No materials uploaded yet.
</div>

<?php else: ?>

<?php while($m = $materials->fetch_assoc()): ?>

<div class="material-card">

<div class="material-title">
<?=htmlspecialchars($m['title'])?>
</div>

<?php if(!empty($m['description'])): ?>

<p>
<?=nl2br(htmlspecialchars($m['description']))?>
</p>

<?php endif; ?>

<div class="material-meta">

Uploaded by:
<?=htmlspecialchars($m['faculty_name'])?>

|

<?=date(
"d M Y h:i A",
strtotime($m['created_at'])
)?>

</div>

<a
class="download-btn"
href="uploads/study_materials/<?=urlencode($m['file_name'])?>"
target="_blank"
>
View / Download
</a>

</div>

<?php endwhile; ?>

<?php endif; ?>

<?php endif; ?>

</div>

</body>
</html>