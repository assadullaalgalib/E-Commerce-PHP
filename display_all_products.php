<?php
session_start();
include('Includes/connect.php');
include('Functions/common_function.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>All Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-5">
  <h2>All Products</h2>
  <div class="row">
    <?php
    // Query to fetch all products
    $query = "SELECT * FROM products ORDER BY product_id DESC";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            display_product_card($row);  // Call function from common_function.php
        }
    } else {
        echo "<p class='text-center text-danger'>No products found.</p>";
    }
    ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
