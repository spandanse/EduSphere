<?php
require_once "includes/functions.php";

require_login();

if($_SESSION['role'] != 'faculty'){
    die("Access denied");
}

$uid = $_SESSION['user_id'];

if(isset($_GET['delete'])){

    delete_material($_GET['delete']);
}

$materials = get_materials_by_faculty($uid);
?>

<!doctype html>
<html>
<head>

<meta charset="utf-8">

<title>Manage Materials</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container">

<div style="margin-top:15px;margin-bottom:20px;">

<a
href="upload_material.php"
class="btn"
>
← Upload More Materials
</a>

</div>

<h2 style="margin-top:20px;">
Manage Study Materials
</h2>

<?php if(empty($materials)): ?>

<div class="card">
No materials uploaded yet.
</div>

<?php else: ?>

<?php foreach($materials as $m): ?>

<div class="card" style="margin-bottom:15px;">

<h3>
<?=htmlspecialchars($m['title'])?>
</h3>

<p>
<?=nl2br(htmlspecialchars($m['description']))?>
</p>

<small>

Subject:
<?=htmlspecialchars($m['subject_name'])?>

<br><br>

<?=date(
"d M Y h:i A",
strtotime($m['created_at'])
)?>

</small>

<br><br>

<a
class="btn"
href="uploads/study_materials/<?=$m['file_name']?>"
download
>
Download
</a>

<a
class="btn"
style="background:red;margin-left:10px;"
href="?delete=<?=$m['id']?>"
onclick="return confirm('Delete this material?')"
>
Delete
</a>

</div>

<?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>