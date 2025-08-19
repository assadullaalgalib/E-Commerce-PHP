<?php
session_start();
include('../Includes/connect.php'); // Adjust the path if needed

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch role also
    $sql = "SELECT user_id, username, password_hash, role FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_id, $username, $password_hash, $role);

        if (mysqli_stmt_fetch($stmt)) {
            // User found, verify password
            if (password_verify($password, $password_hash)) {
                // Password correct, set session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;

                // Redirect based on role
                if ($role === 'admin') {
                    header("Location: ../Admin/index.php"); // Admin dashboard
                } else {
                    header("Location: ../index.php"); // Customer home
                }
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "Database error: failed to prepare statement.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Login</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" class="form-control" required autofocus />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required />
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="mt-3 text-center">
        Don't have an account? <a href="register.php">Register here</a>.
    </p>
</div>
</body>
</html>
