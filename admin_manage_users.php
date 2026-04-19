<?php
session_start();
require_once "includes/db.php";
require_once "includes/functions.php";

check_admin();

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container" style="max-width:800px; margin:40px auto;">
    <div class="card">
        <h2>All Registered Users</h2>

        <table class="table">
            <thead>
                <tr>
                    <th style="width:10%;">ID</th>
                    <th style="width:30%;">Name</th>
                    <th style="width:40%;">Email</th>
                    <th style="width:20%;">Role</th>
                </tr>
            </thead>

            <tbody>
            <?php while ($row = mysqli_fetch_assoc($users)) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= ucfirst($row['role']) ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <div style="margin-top:20px; text-align:center;">
            <a href="dashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>