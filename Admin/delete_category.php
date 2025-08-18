<?php
include('../Includes/connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Optional: check if category exists first
    $check = $con->prepare("SELECT * FROM categories WHERE category_id=?");
    $check->bind_param("i", $id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Delete the category
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
} else {
    echo "<script>alert('Invalid ID'); window.location.href='view_categories.php';</script>";
}
?>
