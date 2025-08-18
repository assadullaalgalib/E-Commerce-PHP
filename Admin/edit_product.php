<?php
include('../Includes/connect.php'); // Make sure this path is correct

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Fetch existing product data
    $stmt = $con->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

if (isset($_POST['update_product'])) {
    $title = $_POST['product_title'];
    $description = $_POST['product_description'];
    $keywords = $_POST['product_keywords'];
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $price = $_POST['product_price'];

    // Image handling
    $image1 = $product['product_image1'];
    $image2 = $product['product_image2'];
    $image3 = $product['product_image3'];

    if (!empty($_FILES['product_image1']['name'])) {
        $image1 = time() . "_" . $_FILES['product_image1']['name'];
        move_uploaded_file($_FILES['product_image1']['tmp_name'], "../images/$image1");
    }
    if (!empty($_FILES['product_image2']['name'])) {
        $image2 = time() . "_" . $_FILES['product_image2']['name'];
        move_uploaded_file($_FILES['product_image2']['tmp_name'], "../images/$image2");
    }
    if (!empty($_FILES['product_image3']['name'])) {
        $image3 = time() . "_" . $_FILES['product_image3']['name'];
        move_uploaded_file($_FILES['product_image3']['tmp_name'], "../images/$image3");
    }

    // Update query
    $stmt = $con->prepare("UPDATE products SET product_title=?, product_description=?, product_keywords=?, category_id=?, brand_id=?, product_image1=?, product_image2=?, product_image3=?, product_price=? WHERE product_id=?");
    $stmt->bind_param("sssissssdi", $title, $description, $keywords, $category_id, $brand_id, $image1, $image2, $image3, $price, $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully'); window.location.href='View_product.php';</script>";
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}
?>

<h2>Edit Product</h2>
<form method="post" enctype="multipart/form-data">
    <label>Title:</label><br>
    <input type="text" name="product_title" value="<?= htmlspecialchars($product['product_title']) ?>"><br><br>

    <label>Description:</label><br>
    <textarea name="product_description"><?= htmlspecialchars($product['product_description']) ?></textarea><br><br>

    <label>Keywords:</label><br>
    <input type="text" name="product_keywords" value="<?= htmlspecialchars($product['product_keywords']) ?>"><br><br>

    <label>Category ID:</label><br>
    <input type="number" name="category_id" value="<?= $product['category_id'] ?>"><br><br>

    <label>Brand ID:</label><br>
    <input type="number" name="brand_id" value="<?= $product['brand_id'] ?>"><br><br>

    <label>Price:</label><br>
    <input type="text" name="product_price" value="<?= $product['product_price'] ?>"><br><br>

    <label>Image 1:</label><br>
    <input type="file" name="product_image1"><br>
    <img src="../images/<?= $product['product_image1'] ?>" width="80"><br><br>

    <label>Image 2:</label><br>
    <input type="file" name="product_image2"><br>
    <img src="../images/<?= $product['product_image2'] ?>" width="80"><br><br>

    <label>Image 3:</label><br>
    <input type="file" name="product_image3"><br>
    <img src="../images/<?= $product['product_image3'] ?>" width="80"><br><br>

    <button type="submit" name="update_product">Update Product</button>
</form>
