<?php
include('../Includes/connect.php');

// Fetch brands from the database
$query = "SELECT * FROM brands";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Brands</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">All Brands</h2>
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>Brand ID</th>
                    <th>Brand Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['brand_id'];
                    $title = $row['brand_title'];
                    echo "<tr>
                            <td>$id</td>
                            <td>$title</td>
                            <td>
                                <a href='edit.php?id=$id&type=brand' class='btn btn-sm btn-warning'>Edit</a>
                                <a href='delete.php?id=$id&type=brand' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this brand?\");'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
