<?php
session_start();
include('../Includes/connect.php');

if (isset($_POST['register'])) {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $cpassword  = $_POST['confirm_password'];
    $contact    = trim($_POST['contact']);
    $role       = $_POST['role'];
    $user_image = $_FILES['user_image']['name'];
    $tmp_image  = $_FILES['user_image']['tmp_name'];

    if ($password !== $cpassword) {
        $error = "Passwords do not match!";
    } else {
        // Check username/email duplicates
        $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username or email already taken!";
        } else {
            // Handle image upload
            $image_path = null;
            if (!empty($user_image)) {
                $image_folder = __DIR__ . '/../Images/';
                if (!is_dir($image_folder)) mkdir($image_folder, 0755, true);
                $image_path = basename($user_image);
                move_uploaded_file($tmp_image, $image_folder . $image_path);
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($con, "INSERT INTO users (username, email, contact, user_image, password_hash, role) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $contact, $image_path, $hashed_password, $role);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/auth.css">
</head>
<body>
<div class="auth-container d-flex justify-content-center align-items-center min-vh-100">
    <div class="auth-card p-4 shadow rounded w-100" style="max-width: 400px;">
        <h2 class="mb-4 text-center">Create Account</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">User Role</label>
                <select name="role" class="form-control" required>
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">User Image (optional)</label>
                <input type="file" name="user_image" class="form-control" accept="image/*">
            </div>
            <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="mt-3 text-center">
            Already have an account? <a href="login.php">Login here</a>.
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
