<?php
include('../Includes/connect.php');

if (!isset($_GET['id'])) {
    echo "<script>alert('No category ID provided'); window.location.href='view_categories.php';</script>";
    exit();
}

$category_id = intval($_GET['id']);

// Fetch existing category details
$stmt = $con->prepare("SELECT category_title FROM categories WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Category not found'); window.location.href='view_categories.php';</script>";
    exit();
}

$category = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['update_category'])) {
    $new_title = trim($_POST['category_title']);

    if (!empty($new_title)) {
        $update_stmt = $con->prepare("UPDATE categories SET category_title = ? WHERE category_id = ?");
        $update_stmt->bind_param("si", $new_title, $category_id);
        if ($update_stmt->execute()) {
            echo "<script>alert('Category updated successfully'); window.location.href='view_categories.php';</script>";
            exit();
        } else {
            $error = "Error updating category.";
        }
    } else {
        $error = "Category title cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center text-success mb-4">Edit Category</h2>

    <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST" class="w-50 mx-auto">
        <div class="mb-3">
            <label for="category_title" class="form-label">Category Title</label>
            <input type="text" name="category_title" id="category_title" class="form-control" 
                   value="<?php echo htmlspecialchars($category['category_title']); ?>" required>
        </div>
        <button type="submit" name="update_category" class="btn btn-success">Update Category</button>
        <a href="view_categories.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
