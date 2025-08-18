<?php
include('../Includes/connect.php');

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // First, delete product images from folder
    $stmt = $con->prepare("SELECT product_image1, product_image2, product_image3 FROM products WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
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

        // Delete from DB
        $stmt = $con->prepare("DELETE FROM products WHERE product_id=?");
        $stmt->bind_param("i", $product_id);
        if ($stmt->execute()) {
            echo "<script>alert('Product deleted successfully!'); window.location='View_product.php';</script>";
        } else {
            echo "Error deleting product: " . $con->error;
        }
    }
}
?>
