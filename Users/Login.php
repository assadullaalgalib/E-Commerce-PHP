<?php
session_start();
include('../Includes/connect.php');
include('../Functions/common_function.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch user from DB (table 'users' with columns: id, username, password_hash)
    $stmt = mysqli_prepare($con, "SELECT id, password_hash FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id, $password_hash);
    if (mysqli_stmt_fetch($stmt)) {
        // Verify password hash
        if (password_verify($password, $password_hash)) {
            // Login success - save session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            // Redirect to intended page after login (default: index.php)
            $redirect_to = isset($_GET['redirect_to']) ? $_GET['redirect_to'] : 'index.php';
            header("Location: $redirect_to");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5" style="max-width: 400px;">
  <h2 class="mb-4 text-center">User Login</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo esc($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" id="username" name="username" class="form-control" required autofocus />
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" id="password" name="password" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
