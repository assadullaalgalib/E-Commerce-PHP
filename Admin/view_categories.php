<?php
include('../Includes/connect.php');

// Fetch categories from DB
$query = "SELECT * FROM categories";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-success mb-4">All Categories</h2>
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>Category ID</th>
                    <th>Category Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['category_id'];
                    $title = $row['category_title'];
                    echo "<tr>
                            <td>$id</td>
                            <td>$title</td>
                            <td>
                                <a href='edit_category.php?id=$id' class='btn btn-sm btn-primary'>Edit</a>
                                <a href='delete_category.php?id=$id' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this category?\");'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
