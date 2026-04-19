<?php
// index.php
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
  <title>EduSphere — Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    /* Center login box */
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(135deg, #1a73e8, #4dabf7);
    }

    .login-box {
      width: 360px;
      background: #fff;
      padding: 30px 28px;
      border-radius: 12px;
      box-shadow: 0 12px 25px rgba(0,0,0,0.15);
      text-align: center;
    }

    .login-box h2 {
      margin-bottom: 20px;
      color: #1a73e8;
      font-size: 2rem;
      font-weight: 700;
    }

    .login-box label {
      display: block;
      text-align: left;
      margin-bottom: 6px;
      font-weight: 500;
      color: #333;
    }

    .login-box input {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 16px;
      border: 1px solid #e6e9ef;
      border-radius: 8px;
      font-size: 1rem;
    }

    .login-box button {
      width: 100%;
      padding: 10px 0;
      background: #1a73e8;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    .login-box button:hover {
      background: #155dc0;
    }

    .login-box .alert {
      margin-bottom: 15px;
    }

    .login-box p {
      font-size: 0.85rem;
      color: #555;
      margin-top: 12px;
      line-height: 1.4;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>EduSphere</h2>
    <?php if($err): ?>
      <div class="alert error"><?=htmlspecialchars($err)?></div>
    <?php endif; ?>
    <form method="post" onsubmit="return validateLoginForm()">
      <label>Email</label>
      <input type="email" name="email" placeholder="Enter your email" required>
      <label>Password</label>
      <input type="password" name="password" placeholder="Enter your password" required>
      <button type="submit">Login</button>
    </form>
    <p>
      <strong>Sample accounts:</strong><br>
      Student: student1@example.com / password123<br>
      Faculty: faculty1@example.com / password123<br>
      Admin: admin@example.com / admin123
    </p>
  </div>

<script>
function validateLoginForm(){
  return true;
}
</script>
</body>
</html>