<?php
session_start();
include(__DIR__ . '/../Includes/connect.php'); // Adjust this path if needed

if (isset($_POST['register'])) {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $cpassword  = $_POST['confirm_password'];
    $contact    = trim($_POST['contact']);
    $user_image = $_FILES['user_image']['name'];
    $tmp_image  = $_FILES['user_image']['tmp_name'];

    // Basic validation
    if ($password !== $cpassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($con, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email already registered!');</script>";
        } else {
            // Handle user image upload
            if (!empty($user_image)) {
                $image_folder = __DIR__ . '/../User_images/'; // Adjust this path
                if (!is_dir($image_folder)) {
                    mkdir($image_folder, 0755, true);
                }
                $image_path = $image_folder . basename($user_image);
                if (!move_uploaded_file($tmp_image, $image_path)) {
                    echo "<script>alert('Failed to upload image.');</script>";
                    $user_image = null;
                } else {
                    $user_image = basename($user_image); // store only filename in DB
                }
            } else {
                $user_image = null;
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database with correct column names
            $insert_query = "INSERT INTO users (username, email, contact, user_image, password_hash) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $insert_query);
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Create Account</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required />
        </div>

        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" required />
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required />
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required />
        </div>

        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact" class="form-control" required />
        </div>

        <div class="mb-3">
            <label class="form-label">User Image (optional)</label>
            <input type="file" name="user_image" class="form-control" accept="image/*" />
        </div>

        <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
    </form>
</div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Handle form submission
}
?>