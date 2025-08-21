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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container my-5">

    <!-- Header with Insert Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-success">All Categories</h2>
        <a href="insert_category.php" class="btn btn-success"><i class="fas fa-plus"></i> Insert Category</a>
    </div>

    <!-- Responsive Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
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
                    $title = htmlspecialchars($row['category_title']);
                    echo "<tr>
                            <td>$id</td>
                            <td>$title</td>
                            <td>
                                <a href='edit.php?id=$id&type=category' class='btn btn-sm btn-primary mb-1'><i class='fas fa-edit'></i> Edit</a>
                                <a href='delete.php?id=$id&type=category' class='btn btn-sm btn-danger mb-1' onclick='return confirm(\"Are you sure you want to delete this category?\");'><i class='fas fa-trash'></i> Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
