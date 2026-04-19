<?php
require_once "includes/functions.php";
require_role('student');

$uid = $_SESSION['user_id'];
$msg="";

if($_SERVER['REQUEST_METHOD']=="POST"){

    send_query(
        $uid,
        $_POST['faculty_id'],
        $_POST['subject'],
        $_POST['message']
    );

    $msg="Query sent successfully.";
}

$queries = get_student_queries($uid);

/* get faculty list */
$faculty = $conn->query("SELECT id,name FROM users WHERE role='faculty'");
?>

<!doctype html>
<html>
<head>
<title>Send Query</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="container">

    <!-- Back to Dashboard Button -->
    <a class="btn" href="dashboard.php" style="margin-bottom:15px; display:inline-block;">← Back to Dashboard</a>

    <h3>Feedback & Query Module</h3>

    <?php if($msg): ?>
    <div class="alert success"><?=$msg?></div>
    <?php endif; ?>

    <div class="card">

        <h4>Send Query</h4>

        <form method="POST">

            <label>Select Faculty</label>
            <select name="faculty_id" required>
            <?php while($f=$faculty->fetch_assoc()): ?>
                <option value="<?=$f['id']?>"><?=$f['name']?></option>
            <?php endwhile; ?>
            </select>

            <label>Subject</label>
            <input type="text" name="subject" required>

            <label>Your Message</label>
            <textarea name="message" required style="width:100%;height:100px;"></textarea>

            <br><br>

            <button class="btn">Send Query</button>

        </form>

    </div>

    <div class="card">

        <h4>Your Queries</h4>

        <table class="table">

        <tr>
            <th>Faculty</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Reply</th>
            <th>Status</th>
        </tr>

        <?php foreach($queries as $q): ?>
        <tr>
            <td><?=$q['faculty_name']?></td>
            <td><?=$q['subject']?></td>
            <td><?=$q['message']?></td>
            <td><?= $q['reply'] ? $q['reply'] : "Waiting for reply" ?></td>
            <td><?=$q['status']?></td>
        </tr>
        <?php endforeach; ?>

        </table>

    </div>

</div>

</body>
</html>