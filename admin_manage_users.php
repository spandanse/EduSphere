<?php
session_start();

require_once "includes/db.php";
require_once "includes/functions.php";

check_admin();

/* DELETE USER */

if(isset($_GET['delete'])){

    $delete_id = (int)$_GET['delete'];

    /* Prevent admin deleting self */
    if($delete_id != $_SESSION['user_id']){

        $stmt = $conn->prepare("
            DELETE FROM users
            WHERE id = ?
        ");

        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
    }
}

$users = mysqli_query(
    $conn,
    "SELECT * FROM users ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<title>Manage Users - Admin</title>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="container" style="max-width:900px; margin:40px auto;">

    <div class="card">

        <h2>All Registered Users</h2>

        <table class="table">

            <thead>

                <tr>
                    <th style="width:8%;">ID</th>
                    <th style="width:25%;">Name</th>
                    <th style="width:32%;">Email</th>
                    <th style="width:15%;">Role</th>
                    <th style="width:20%;">Action</th>
                </tr>

            </thead>

            <tbody>

            <?php while ($row = mysqli_fetch_assoc($users)) { ?>

                <tr>

                    <td>
                        <?= $row['id'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['email']) ?>
                    </td>

                    <td>
                        <?= ucfirst($row['role']) ?>
                    </td>

                    <td>

                    <?php if($row['id'] != $_SESSION['user_id']){ ?>

                        <a
                        href="?delete=<?=$row['id']?>"
                        class="btn"
                        style="background:#dc3545;"
                        onclick="return confirm('Delete this user?')"
                        >
                        Delete
                        </a>

                    <?php } else { ?>

                        <span style="
                            color:gray;
                            font-weight:bold;
                        ">
                            Current Admin
                        </span>

                    <?php } ?>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

        <div style="
            margin-top:20px;
            text-align:center;
        ">

            <a href="dashboard.php" class="btn">
                Back to Dashboard
            </a>

        </div>

    </div>

</div>

</body>
</html>