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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Brands</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">

    <!-- Header with Insert Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">All Brands</h2>
        <a href="insert_brands.php" class="btn btn-success"><i class="fas fa-plus"></i> Insert Brand</a>
    </div>

    <!-- Responsive Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
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
                    $title = htmlspecialchars($row['brand_title']);
                    echo "<tr>
                            <td>$id</td>
                            <td>$title</td>
                            <td>
                                <a href='edit.php?id=$id&type=brand' class='btn btn-sm btn-warning mb-1'><i class='fas fa-edit'></i> Edit</a>
                                <a href='delete.php?id=$id&type=brand' class='btn btn-sm btn-danger mb-1' onclick='return confirm(\"Are you sure you want to delete this brand?\");'><i class='fas fa-trash'></i> Delete</a>
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
