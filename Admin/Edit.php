<?php
session_start();
include('../Includes/connect.php');

// Check if type and id exist
if (!isset($_GET['type']) || !isset($_GET['id'])) {
    echo "<script>alert('Invalid request'); window.location.href='dashboard.php';</script>";
    exit();
}

$type = $_GET['type'];
$id   = intval($_GET['id']);

$error = "";

/* =========================
   BRAND EDIT
========================= */
if ($type === "brand") {
    $stmt = $con->prepare("SELECT brand_title FROM brands WHERE brand_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $brand = $stmt->get_result()->fetch_assoc();

    if (isset($_POST['update_brand'])) {
        $new_title = trim($_POST['brand_title']);
        if (!empty($new_title)) {
            $update = $con->prepare("UPDATE brands SET brand_title=? WHERE brand_id=?");
            $update->bind_param("si", $new_title, $id);
            if ($update->execute()) {
                echo "<script>alert('Brand updated'); window.location.href='view_brands.php';</script>";
                exit();
            }
        } else {
            $error = "Brand title cannot be empty.";
        }
    }
}

/* =========================
   CATEGORY EDIT
========================= */
if ($type === "category") {
    $stmt = $con->prepare("SELECT category_title FROM categories WHERE category_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $category = $stmt->get_result()->fetch_assoc();

    if (isset($_POST['update_category'])) {
        $new_title = trim($_POST['category_title']);
        if (!empty($new_title)) {
            $update = $con->prepare("UPDATE categories SET category_title=? WHERE category_id=?");
            $update->bind_param("si", $new_title, $id);
            if ($update->execute()) {
                echo "<script>alert('Category updated'); window.location.href='view_categories.php';</script>";
                exit();
            }
        } else {
            $error = "Category title cannot be empty.";
        }
    }
}

/* =========================
   PRODUCT EDIT
========================= */
if ($type === "product") {
    $stmt = $con->prepare("SELECT * FROM products WHERE product_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (isset($_POST['update_product'])) {
        $title = $_POST['product_title'];
        $description = $_POST['product_description'];
        $keywords = $_POST['product_keywords'];
        $category_id = $_POST['category_id'];
        $brand_id = $_POST['brand_id'];
        $price = $_POST['product_price'];

        // Image handling
        $image1 = $product['product_image1'];
        if (!empty($_FILES['product_image1']['name'])) {
            $image1 = time()."_".$_FILES['product_image1']['name'];
            move_uploaded_file($_FILES['product_image1']['tmp_name'], "../images/$image1");
        }

        $stmt = $con->prepare("UPDATE products SET product_title=?, product_description=?, product_keywords=?, category_id=?, brand_id=?, product_image1=?, product_price=? WHERE product_id=?");
        $stmt->bind_param("sssissdi", $title, $description, $keywords, $category_id, $brand_id, $image1, $price, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Product updated'); window.location.href='view_products.php';</script>";
            exit();
        }
    }
}

/* =========================
   USER EDIT
========================= */
if ($type === "user") {
    $stmt = $con->prepare("SELECT username,email,contact,user_image FROM users WHERE user_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST['username']);
        $email    = trim($_POST['email']);
        $contact  = trim($_POST['contact']);
        $image_path = $user['user_image'];

        if (!empty($_FILES['user_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['user_image']['name'], PATHINFO_EXTENSION));
            $new_filename = "profile_" . $id . "_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES['user_image']['tmp_name'], "../Images/$new_filename")) {
                if (!empty($user['user_image']) && file_exists("../Images/".$user['user_image'])) {
                    unlink("../Images/".$user['user_image']);
                }
                $image_path = $new_filename;
            }
        }

        $stmt = $con->prepare("UPDATE users SET username=?, email=?, contact=?, user_image=? WHERE user_id=?");
        $stmt->bind_param("ssssi", $username, $email, $contact, $image_path, $id);
        if ($stmt->execute()) {
            echo "<script>alert('User updated'); window.location.href='all_users.php';</script>";
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<?php if ($type === "brand") { ?>
  <h2>Edit Brand</h2>
  <form method="POST">
    <input type="text" name="brand_title" class="form-control mb-3" value="<?= htmlspecialchars($brand['brand_title']); ?>">
    <button type="submit" name="update_brand" class="btn btn-success">Update Brand</button>
  </form>

<?php } elseif ($type === "category") { ?>
  <h2>Edit Category</h2>
  <form method="POST">
    <input type="text" name="category_title" class="form-control mb-3" value="<?= htmlspecialchars($category['category_title']); ?>">
    <button type="submit" name="update_category" class="btn btn-success">Update Category</button>
  </form>

<?php } elseif ($type === "product") { ?>
  <h2>Edit Product</h2>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="product_title" class="form-control mb-2" value="<?= $product['product_title'] ?>">
    <textarea name="product_description" class="form-control mb-2"><?= $product['product_description'] ?></textarea>
    <input type="text" name="product_keywords" class="form-control mb-2" value="<?= $product['product_keywords'] ?>">
    <input type="number" name="category_id" class="form-control mb-2" value="<?= $product['category_id'] ?>">
    <input type="number" name="brand_id" class="form-control mb-2" value="<?= $product['brand_id'] ?>">
    <input type="text" name="product_price" class="form-control mb-2" value="<?= $product['product_price'] ?>">
    <input type="file" name="product_image1" class="form-control mb-2">
    <img src="../images/<?= $product['product_image1'] ?>" width="80"><br>
    <button type="submit" name="update_product" class="btn btn-success">Update Product</button>
  </form>

<?php } elseif ($type === "user") { ?>
  <h2>Edit User</h2>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="username" class="form-control mb-2" value="<?= $user['username'] ?>">
    <input type="email" name="email" class="form-control mb-2" value="<?= $user['email'] ?>">
    <input type="text" name="contact" class="form-control mb-2" value="<?= $user['contact'] ?>">
    <input type="file" name="user_image" class="form-control mb-2">
    <?php if (!empty($user['user_image'])): ?>
      <img src="../Images/<?= $user['user_image'] ?>" width="100">
    <?php endif; ?>
    <button type="submit" class="btn btn-success">Update User</button>
  </form>
<?php } ?>

</body>
</html>
