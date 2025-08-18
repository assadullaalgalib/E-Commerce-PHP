<?php
session_start();
include('../Includes/connect.php'); // Adjust path if needed

if (isset($_POST['register'])) {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $cpassword  = $_POST['confirm_password'];
    $contact    = trim($_POST['contact']);
    $user_image = $_FILES['user_image']['name'];
    $tmp_image  = $_FILES['user_image']['tmp_name'];

    // Check passwords match
    if ($password !== $cpassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Check username duplicate
        $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result_username = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result_username) > 0) {
            echo "<script>alert('Username already taken!');</script>";
        } else {
            // Check email duplicate
            $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result_email = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result_email) > 0) {
                echo "<script>alert('Email already registered!');</script>";
            } else {
                // Handle image upload
                if (!empty($user_image)) {
                    $image_folder = __DIR__ . '/../Images/';
                    if (!is_dir($image_folder)) {
                        mkdir($image_folder, 0755, true);
                    }
                    $image_path = $image_folder . basename($user_image);
                    if (!move_uploaded_file($tmp_image, $image_path)) {
                        echo "<script>alert('Failed to upload image.');</script>";
                        $user_image = null;
                    } else {
                        $user_image = basename($user_image); // store only filename
                    }
                } else {
                    $user_image = null;
                }

                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert into database
                $stmt = mysqli_prepare($con, "INSERT INTO users (username, email, contact, user_image, password_hash) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $contact, $user_image, $hashed_password);
                $run = mysqli_stmt_execute($stmt);

                if ($run) {
                    echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Registration failed. Please try again.');</script>";
                }
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
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Create Account</h2>
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
            <label class="form-label">User Image (optional)</label>
            <input type="file" name="user_image" class="form-control" accept="image/*">
        </div>
        <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
