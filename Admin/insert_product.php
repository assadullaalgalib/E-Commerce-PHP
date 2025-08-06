<?php
include('../Includes/connect.php');

if (isset($_POST['insert_product'])) {
    $product_title = trim($_POST['product_title'] ?? '');
    $product_description = trim($_POST['product_description'] ?? '');
    $product_keywords = trim($_POST['product_keywords'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $brand_id = intval($_POST['brand_id'] ?? 0);
    $product_price = floatval($_POST['product_price'] ?? 0);

    $product_image1 = $_FILES['product_image1']['name'] ?? '';
    $product_image2 = $_FILES['product_image2']['name'] ?? '';
    $product_image3 = $_FILES['product_image3']['name'] ?? '';

    $temp_image1 = $_FILES['product_image1']['tmp_name'] ?? '';
    $temp_image2 = $_FILES['product_image2']['tmp_name'] ?? '';
    $temp_image3 = $_FILES['product_image3']['tmp_name'] ?? '';

    if ($product_image1 && $temp_image1) {
        move_uploaded_file($temp_image1, "../images/$product_image1");
    }
    if ($product_image2 && $temp_image2) {
        move_uploaded_file($temp_image2, "../images/$product_image2");
    }
    if ($product_image3 && $temp_image3) {
        move_uploaded_file($temp_image3, "../images/$product_image3");
    }

    $insert_query = "INSERT INTO products 
        (product_title, product_description, product_keywords, category_id, brand_id,
         product_image1, product_image2, product_image3, product_price, date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($con, $insert_query);
    if (!$stmt) {
        die('Prepare failed: ' . mysqli_error($con));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssissssd",
        $product_title,
        $product_description,
        $product_keywords,
        $category_id,
        $brand_id,
        $product_image1,
        $product_image2,
        $product_image3,
        $product_price
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Product inserted successfully.'); window.location.href='insert_product.php';</script>";
    } else {
        echo "<script>alert('Failed to insert product: " . mysqli_stmt_error($stmt) . "');</script>";
    }
}
?>

<h2>Insert New Product</h2>

<form action="insert_product.php" method="post" enctype="multipart/form-data">

    <!-- Product Title -->
    <label for="product_title">Product Title:</label><br>
    <input type="text" name="product_title" id="product_title" placeholder="Enter Product Title" required><br><br>

    <!-- Product Description -->
    <label for="product_description">Product Description:</label><br>
    <textarea name="product_description" id="product_description" placeholder="Enter Product Description" required></textarea><br><br>

    <!-- Product Keywords -->
    <label for="product_keywords">Product Keywords:</label><br>
    <input type="text" name="product_keywords" id="product_keywords" placeholder="Enter Keywords" required><br><br>

    <!-- Product Category -->
    <label for="category_id">Select Category:</label><br>
    <select name="category_id" id="category_id" required>
        <option value="">-- Select Category --</option>
        <?php
        $categories_result = mysqli_query($con, "SELECT category_id, category_title FROM categories");
        while ($cat = mysqli_fetch_assoc($categories_result)) {
            echo "<option value='" . $cat['category_id'] . "'>" . htmlspecialchars($cat['category_title']) . "</option>";
        }
        ?>
    </select><br><br>

    <!-- Product Brand -->
    <label for="brand_id">Select Brand:</label><br>
    <select name="brand_id" id="brand_id" required>
        <option value="">-- Select Brand --</option>
        <?php
        $brands_result = mysqli_query($con, "SELECT brand_id, brand_title FROM brands");
        while ($brand = mysqli_fetch_assoc($brands_result)) {
            echo "<option value='" . $brand['brand_id'] . "'>" . htmlspecialchars($brand['brand_title']) . "</option>";
        }
        ?>
    </select><br><br>

    <!-- Product Image 1 -->
    <label for="product_image1">Product Image 1 (Required):</label><br>
    <input type="file" name="product_image1" id="product_image1" required><br><br>

    <!-- Product Image 2 -->
    <label for="product_image2">Product Image 2 (Optional):</label><br>
    <input type="file" name="product_image2" id="product_image2"><br><br>

    <!-- Product Image 3 -->
    <label for="product_image3">Product Image 3 (Optional):</label><br>
    <input type="file" name="product_image3" id="product_image3"><br><br>

    <!-- Product Price -->
    <label for="product_price">Product Price:</label><br>
    <input type="number" step="0.01" name="product_price" id="product_price" placeholder="Enter Product Price" required><br><br>

    <!-- Submit Button -->
    <input type="submit" name="insert_product" value="Insert Product">
</form>
