<?php
require_once "includes/functions.php";

require_login();

if($_SESSION['role'] != 'student'){
    die("Access denied");
}

$uid = $_SESSION['user_id'];

$notices = get_student_notices($uid);
?>

<!doctype html>
<html>
<head>

<meta charset="utf-8">
<title>Notice Board</title>

<link rel="stylesheet" href="assets/css/style.css">

<style>

.notice-card{
    padding:15px;
    margin-bottom:15px;
    border-radius:10px;
    background:#fff;
    border:1px solid #e5e7eb;
}

.notice-new{
    background:#fff8db;
    border:2px solid #facc15;
}

.notice-badge{
    background:red;
    color:white;
    padding:4px 8px;
    border-radius:20px;
    font-size:12px;
    margin-left:10px;
}

</style>

</head>

<body>

<div class="container">

<a href="dashboard.php" class="btn">
← Back to Dashboard
</a>

<h2 style="margin-top:20px;">
Notice Board
</h2>

<br>

<?php if(empty($notices)): ?>

<div class="card">
No notices available.
</div>

<?php else: ?>

<?php foreach($notices as $n): ?>

<div class="notice-card <?= !$n['is_read'] ? 'notice-new' : '' ?>">

<h3 style="margin-top:0;">

<?=htmlspecialchars($n['title'])?>

<?php if(!$n['is_read']): ?>

<span class="notice-badge">
NEW
</span>

<?php endif; ?>

</h3>

<p style="line-height:1.7;">
<?=nl2br(htmlspecialchars($n['message']))?>
</p>

<hr style="margin:15px 0;">

<small>

By:
<?=htmlspecialchars($n['uploader_name'])?>

<?php if($n['subject_name']): ?>
|
Subject:
<?=htmlspecialchars($n['subject_name'])?>
<?php endif; ?>

<br><br>

<?=date(
"d M Y h:i A",
strtotime($n['created_at'])
)?>

</small>

</div>

<?php
if(!$n['is_read']){
    mark_notice_read($n['id'], $uid);
}
?>

<?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>