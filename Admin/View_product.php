<?php
include('../Includes/connect.php');
?>

<h2>All Products</h2>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Sr No</th>
        <th>Product Title</th>
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

    <?php
    $get_products = "SELECT * FROM products";
    $result = mysqli_query($con, $get_products);
    $sr_no = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];

        echo "<tr>";
        echo "<td>" . $sr_no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['product_title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['product_description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['product_keywords']) . "</td>";

        // Get category name
        $cat_id = $row['category_id'];
        $cat_query = mysqli_query($con, "SELECT category_title FROM categories WHERE category_id = $cat_id");
        $cat_row = mysqli_fetch_assoc($cat_query);
        echo "<td>" . htmlspecialchars($cat_row['category_title'] ?? 'N/A') . "</td>";

        // Get brand name
        $brand_id = $row['brand_id'];
        $brand_query = mysqli_query($con, "SELECT brand_title FROM brands WHERE brand_id = $brand_id");
        $brand_row = mysqli_fetch_assoc($brand_query);
        echo "<td>" . htmlspecialchars($brand_row['brand_title'] ?? 'N/A') . "</td>";

        // Images
        echo "<td><img src='../images/" . htmlspecialchars($row['product_image1']) . "' width='60'></td>";
        echo "<td><img src='../images/" . htmlspecialchars($row['product_image2']) . "' width='60'></td>";
        echo "<td><img src='../images/" . htmlspecialchars($row['product_image3']) . "' width='60'></td>";

        echo "<td>" . htmlspecialchars($row['product_price']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date']) . "</td>";

        // ✅ Total Sold (assuming "orders_pending" table contains qty and product_id)
        $sold_query = mysqli_query($con, "SELECT SUM(quantity) as total_sold FROM orders_pending WHERE product_id = $product_id");
        $sold_row = mysqli_fetch_assoc($sold_query);
        $total_sold = $sold_row['total_sold'] ?? 0;
        echo "<td>" . (int)$total_sold . "</td>";

        // Actions
        echo "<td>
                <a href='edit.php?type=product&id=$product_id'>Edit</a>
                <a href='delete.php?id=$product_id&type=product' onclick='return confirm(\"Are you sure?\");'>Delete</a>
              </td>";
        echo "</tr>";
    }
    ?>
</table>
