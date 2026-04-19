<?php
require_once "includes/functions.php";
require_role('faculty');

$fid = $_SESSION['user_id'];

/* ✅ MARK ALL QUERIES AS SEEN */
$stmt = $conn->prepare("
    UPDATE queries
    SET seen = 1
    WHERE faculty_id = ?
");
$stmt->bind_param("i", $fid);
$stmt->execute();

if(isset($_POST['reply'])){
    reply_query($_POST['query_id'], $_POST['reply']);
}

$queries = get_faculty_queries($fid);
?>

<!doctype html>
<html>
<head>
<title>Student Queries</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="container">

    <!-- Back to Dashboard Button -->
    <a class="btn" href="dashboard.php" style="margin-bottom:15px; display:inline-block;">← Back to Dashboard</a>

    <h3>Student Queries</h3>

    <table class="table">
        <tr>
            <th>Student</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Reply</th>
        </tr>

        <?php foreach($queries as $q): ?>
        <tr>
            <td><?=$q['student_name']?></td>
            <td><?=$q['subject']?></td>
            <td><?=$q['message']?></td>
            <td>
                <?php if($q['status']=="open"): ?>
                <form method="POST">
                    <input type="hidden" name="query_id" value="<?=$q['id']?>">
                    <textarea name="reply" required style="width:100%;height:80px;"></textarea>
                    <br>
                    <button class="btn">Send Reply</button>
                </form>
                <?php else: ?>
                    <?=$q['reply']?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>