<?php
session_start();
include('../Includes/connect.php');

// Optional: admin authentication check
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit();
// }

if (!isset($_GET['type']) || !isset($_GET['id'])) {
    echo "<script>alert('Invalid request'); window.location.href='index.php';</script>";
    exit();
}

$type = $_GET['type'];   // brand / category / product / user
$id   = intval($_GET['id']);

switch ($type) {

    // ✅ Delete Brand
    case 'brand':
        $check = $con->prepare("SELECT * FROM brands WHERE brand_id=?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows > 0) {
            $stmt = $con->prepare("DELETE FROM brands WHERE brand_id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>alert('Brand deleted successfully'); window.location.href='view_brands.php';</script>";
            } else {
                echo "<script>alert('Error deleting brand'); window.location.href='view_brands.php';</script>";
            }
        } else {
            echo "<script>alert('Brand not found'); window.location.href='view_brands.php';</script>";
        }
        break;

    // ✅ Delete Category
    case 'category':
        $check = $con->prepare("SELECT * FROM categories WHERE category_id=?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows > 0) {
            $stmt = $con->prepare("DELETE FROM categories WHERE category_id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>alert('Category deleted successfully'); window.location.href='view_categories.php';</script>";
            } else {
                echo "<script>alert('Error deleting category'); window.location.href='view_categories.php';</script>";
            }
        } else {
            echo "<script>alert('Category not found'); window.location.href='view_categories.php';</script>";
        }
        break;

    // ✅ Delete Product (with images)
    case 'product':
        $stmt = $con->prepare("SELECT product_image1, product_image2, product_image3 FROM products WHERE product_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            foreach (['product_image1', 'product_image2', 'product_image3'] as $img) {
                $file = "../images/" . $product[$img];
                if (!empty($product[$img]) && file_exists($file)) {
                    unlink($file);
                }
            }
            $stmt = $con->prepare("DELETE FROM products WHERE product_id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>alert('Product deleted successfully!'); window.location='view_products.php';</script>";
            } else {
                echo "<script>alert('Error deleting product'); window.location='view_products.php';</script>";
            }
        } else {
            echo "<script>alert('Product not found'); window.location='view_products.php';</script>";
        }
        break;

    // ✅ Delete User (with profile image)
    case 'user':
        $stmt = $con->prepare("SELECT user_image FROM users WHERE user_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            if (!empty($user['user_image']) && file_exists("../Images/" . $user['user_image'])) {
                unlink("../Images/" . $user['user_image']);
            }
            $delete_stmt = $con->prepare("DELETE FROM users WHERE user_id=?");
            $delete_stmt->bind_param("i", $id);
            if ($delete_stmt->execute()) {
                echo "<script>alert('User deleted successfully!'); window.location='list_users.php';</script>";
            } else {
                echo "<script>alert('Error deleting user'); window.location='list_users.php';</script>";
            }
        } else {
            echo "<script>alert('User not found'); window.location='list_users.php';</script>";
        }
        break;

    default:
        echo "<script>alert('Invalid type'); window.location.href='index.php';</script>";
}
?>
