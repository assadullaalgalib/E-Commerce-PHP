<?php
session_start();
include('../Includes/connect.php');

//Optional: Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../users/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-success">All Users</h2>
        <a href="../users/register.php" class="btn btn-primary"><i class="fas fa-user-plus me-1"></i> Insert User</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>User ID</th>
                    <th>Profile Image</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM users ORDER BY user_id DESC";
            $result = mysqli_query($con, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $user_image_path = "../Images/" . $row['user_image'];
                $user_image = (!empty($row['user_image']) && file_exists($user_image_path))
                              ? $user_image_path
                              : "https://via.placeholder.com/50";

                echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td><img src='{$user_image}' class='profile-img' alt='User Image'></td>
                        <td>{$row['username']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['contact']}</td>
                        <td>
                            <a href='edit.php?id={$row['user_id']}&type=user' class='btn btn-sm btn-warning mb-1'>
                                <i class='fas fa-edit'></i> Edit
                            </a>
                            <a href='delete.php?id={$row['user_id']}&type=user' onclick='return confirm(\"Are you sure you want to delete this user?\");' class='btn btn-sm btn-danger mb-1'>
                                <i class='fas fa-trash-alt'></i> Delete
                            </a>
                        </td>
                      </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-success"><i class="fas fa-home me-1"></i> Back to Home</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>
