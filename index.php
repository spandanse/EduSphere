<?php
require_once "includes/functions.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (login_user($email, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $err = "Invalid email or password.";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>EduSphere Login</title>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  height: 100vh;
  display: flex;
  background: linear-gradient(135deg, #1a73e8, #4dabf7);
}

/* LEFT SIDE */
.left {
  flex: 1;
  color: white;
  padding: 90px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.left h1 {
  font-size: 3.2rem;
  margin-bottom: 10px;
}

.left h3 {
  margin-top: 0;
  font-weight: 400;
  opacity: 0.95;
  font-size: 1.3rem;
}

.left p {
  margin-top: 25px;
  font-size: 1.05rem;
  opacity: 0.9;
  max-width: 420px;
  line-height: 1.6;
}

/* RIGHT SIDE */
.right {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* LOGIN BOX */
.login-box {
  width: 380px;
  background: white;
  padding: 35px;
  border-radius: 14px;
  box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

.login-box h2 {
  text-align: center;
  color: #1a73e8;
  margin-bottom: 5px;
}

.subtitle {
  text-align: center;
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 20px;
}

label {
  font-size: 0.9rem;
  color: #333;
}

input {
  width: 100%;
  padding: 10px;
  margin: 8px 0 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  outline: none;
}

input:focus {
  border-color: #1a73e8;
}

button {
  width: 100%;
  padding: 10px;
  background: #1a73e8;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1rem;
}

button:hover {
  background: #155dc0;
}

.alert {
  color: red;
  text-align: center;
  margin-bottom: 10px;
  font-size: 0.9rem;
}

.demo {
  margin-top: 15px;
  font-size: 0.85rem;
  color: #555;
  background: #f5f7fb;
  padding: 10px;
  border-radius: 8px;
  line-height: 1.5;
}
</style>

</head>

<body>

<!-- LEFT PANEL -->
<div class="left">

  <h1>EduSphere</h1>
  <h3>The Complete Academic Ecosystem</h3>

  <p>
    A unified platform for students and faculty to manage attendance,
    marks, analytics, and academic communication — all in one place.
  </p>

</div>

<!-- RIGHT PANEL -->
<div class="right">

  <div class="login-box">

    <h2>Login</h2>
    <div class="subtitle">Welcome</div>

    <?php if($err): ?>
      <div class="alert"><?=htmlspecialchars($err)?></div>
    <?php endif; ?>

    <form method="post">

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <button type="submit">Login</button>

    </form>

    <div class="demo">
      <b>Demo Credentials:</b><br>
      Student: student1@example.com / student1<br>
      Faculty: faculty1@example.com / faculty1<br>
      Admin: admin@example.com / admin123
    </div>

  </div>

</div>

</body>
</html>