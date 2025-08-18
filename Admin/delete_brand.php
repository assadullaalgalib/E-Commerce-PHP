<?php
include('../Includes/connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: check if brand exists first
    $check = $con->prepare("SELECT * FROM brands WHERE brand_id=?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Delete the brand
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
} else {
    echo "<script>alert('Invalid ID'); window.location.href='view_brands.php';</script>";
}
?>
