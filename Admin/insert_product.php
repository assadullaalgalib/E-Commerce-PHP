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

    if ($product_image1 && $temp_image1) move_uploaded_file($temp_image1, "../images/$product_image1");
    if ($product_image2 && $temp_image2) move_uploaded_file($temp_image2, "../images/$product_image2");
    if ($product_image3 && $temp_image3) move_uploaded_file($temp_image3, "../images/$product_image3");

    $insert_query = "INSERT INTO products 
        (product_title, product_description, product_keywords, category_id, brand_id,
         product_image1, product_image2, product_image3, product_price, date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($con, $insert_query);
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

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Insert Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">Insert New Product</h2>
        <a href="view_products.php" class="btn btn-secondary">Back to Products</a>
    </div>

    <div class="card p-4 shadow-sm bg-white">
        <form action="insert_product.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_title" class="form-label">Product Title</label>
                <input type="text" class="form-control" id="product_title" name="product_title" placeholder="Enter Product Title" required>
            </div>

            <div class="mb-3">
                <label for="product_description" class="form-label">Product Description</label>
                <textarea class="form-control" id="product_description" name="product_description" placeholder="Enter Product Description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="product_keywords" class="form-label">Product Keywords</label>
                <input type="text" class="form-control" id="product_keywords" name="product_keywords" placeholder="Enter Keywords" required>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Select Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php
                        $categories_result = mysqli_query($con, "SELECT category_id, category_title FROM categories");
                        while ($cat = mysqli_fetch_assoc($categories_result)) {
                            echo "<option value='" . $cat['category_id'] . "'>" . htmlspecialchars($cat['category_title']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="brand_id" class="form-label">Select Brand</label>
                    <select class="form-select" id="brand_id" name="brand_id" required>
                        <option value="">-- Select Brand --</option>
                        <?php
                        $brands_result = mysqli_query($con, "SELECT brand_id, brand_title FROM brands");
                        while ($brand = mysqli_fetch_assoc($brands_result)) {
                            echo "<option value='" . $brand['brand_id'] . "'>" . htmlspecialchars($brand['brand_title']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="product_image1" class="form-label">Product Image 1 (Required)</label>
                    <input type="file" class="form-control" id="product_image1" name="product_image1" required>
                </div>
                <div class="col-md-4">
                    <label for="product_image2" class="form-label">Product Image 2 (Optional)</label>
                    <input type="file" class="form-control" id="product_image2" name="product_image2">
                </div>
                <div class="col-md-4">
                    <label for="product_image3" class="form-label">Product Image 3 (Optional)</label>
                    <input type="file" class="form-control" id="product_image3" name="product_image3">
                </div>
            </div>

            <div class="mb-3">
                <label for="product_price" class="form-label">Product Price</label>
                <input type="number" step="0.01" class="form-control" id="product_price" name="product_price" placeholder="Enter Product Price" required>
            </div>

            <button type="submit" class="btn btn-success w-100" name="insert_product">Insert Product</button>
        </form>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
