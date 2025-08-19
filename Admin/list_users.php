<?php
session_start();
include('../Includes/connect.php');

// Optional: Check if admin is logged in
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center text-success mb-4">All Users</h2>

    <table class="table table-bordered table-hover text-center">
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
                        <a href='edit.php?id={$row['user_id']}&type=user' class='btn btn-primary btn-sm'>Edit</a>
                        <a href='delete.php?id={$row['user_id']}&type=user' onclick='return confirm(\"Are you sure you want to delete this user?\");' class='btn btn-danger btn-sm'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
