<?php
include('../Includes/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>All Products</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css"> <!-- Optional extra styling -->
</head>
<body class="bg-light">

<div class="container my-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">All Products</h2>
        <a href="insert_product.php" class="btn btn-success"><i class="fas fa-plus"></i> Insert Product</a>
    </div>

    <!-- Responsive Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Sr No</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Keywords</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Image 1</th>
                    <th>Image 2</th>
                    <th>Image 3</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Total Sold</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $get_products = "SELECT * FROM products";
                $result = mysqli_query($con, $get_products);
                $sr_no = 1;

                while ($row = mysqli_fetch_assoc($result)) {
                    $product_id = $row['product_id'];

                    // Category
                    $cat_id = $row['category_id'];
                    $cat_query = mysqli_query($con, "SELECT category_title FROM categories WHERE category_id = $cat_id");
                    $cat_row = mysqli_fetch_assoc($cat_query);
                    $category = $cat_row['category_title'] ?? 'N/A';

                    // Brand
                    $brand_id = $row['brand_id'];
                    $brand_query = mysqli_query($con, "SELECT brand_title FROM brands WHERE brand_id = $brand_id");
                    $brand_row = mysqli_fetch_assoc($brand_query);
                    $brand = $brand_row['brand_title'] ?? 'N/A';

                    // Total Sold
                    $sold_query = mysqli_query($con, "SELECT SUM(quantity) as total_sold FROM orders_pending WHERE product_id = $product_id");
                    $sold_row = mysqli_fetch_assoc($sold_query);
                    $total_sold = $sold_row['total_sold'] ?? 0;

                    echo "<tr>";
                    echo "<td>$sr_no</td>";
                    echo "<td>" . htmlspecialchars($row['product_title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['product_description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['product_keywords']) . "</td>";
                    echo "<td>" . htmlspecialchars($category) . "</td>";
                    echo "<td>" . htmlspecialchars($brand) . "</td>";
                    echo "<td><img src='../images/" . htmlspecialchars($row['product_image1']) . "' class='img-fluid'></td>";
                    echo "<td><img src='../images/" . htmlspecialchars($row['product_image2']) . "' class='img-fluid'></td>";
                    echo "<td><img src='../images/" . htmlspecialchars($row['product_image3']) . "' class='img-fluid'></td>";
                    echo "<td>৳" . htmlspecialchars($row['product_price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . (int)$total_sold . "</td>";
                    echo "<td>
                            <a href='edit.php?id=$product_id&type=product' class='btn btn-sm btn-primary mb-1'><i class='fas fa-edit'></i> Edit</a>
                            <a href='delete.php?id=$product_id&type=product' onclick='return confirm(\"Are you sure?\");' class='btn btn-sm btn-danger mb-1'><i class='fas fa-trash'></i> Delete</a>
                          </td>";
                    echo "</tr>";
                    $sr_no++;
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
