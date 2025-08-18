<?php
include('../Includes/connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM categories WHERE category_id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
}

if (isset($_POST['update_category'])) {
    $title = $_POST['category_title'];
    $stmt = $con->prepare("UPDATE categories SET category_title=? WHERE category_id=?");
    $stmt->bind_param("si", $title, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Category updated successfully'); window.location.href='view_categories.php';</script>";
    }
}
?>

<h2>Edit Category</h2>
<form method="post">
    <label>Category Title:</label><br>
    <input type="text" name="category_title" value="<?= htmlspecialchars($category['category_title']) ?>" required><br><br>
    <button type="submit" name="update_category" class="btn btn-success">Update Category</button>
</form
