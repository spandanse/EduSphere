<?php
session_start();
require_once "includes/db.php";
require_once "includes/functions.php";

check_admin(); // only admin can access

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];

    $sql = "INSERT INTO users (name, email, password, role) 
            VALUES ('$name', '$email', '$password', '$role')";

    if (mysqli_query($conn, $sql)) {
        $message = "<div class='alert success'>User added successfully!</div>";
    } else {
        $message = "<div class='alert error'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User - Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container" style="max-width:500px; margin:40px auto;">
  <h2>Add New User (Student / Faculty)</h2>

  <?= $message ?>

  <form method="POST">
      <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" name="name" id="name" class="form-control" required>
      </div>

      <div class="form-group">
          <label for="email">Email ID</label>
          <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="form-group">
          <label for="password">Password (Admin enters manually)</label>
          <input type="text" name="password" id="password" class="form-control" required>
      </div>

      <div class="form-group">
          <label for="role">Select Role</label>
          <select name="role" id="role" class="form-control" required>
              <option value="student">Student</option>
              <option value="faculty">Faculty</option>
          </select>
      </div>

      <button class="btn btn-primary mt-3" type="submit">Add User</button>
  </form>

  <div style="margin-top:20px; text-align:center;">
      <a href="dashboard.php">Back to Dashboard</a>
  </div>
</div>
</body>
</html>