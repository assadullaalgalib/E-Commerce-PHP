<?php
include('../Includes/connect.php');

if (!isset($_GET['id'])) {
    echo "<script>alert('No brand ID provided'); window.location.href='view_brands.php';</script>";
    exit();
}

$brand_id = intval($_GET['id']);

// Fetch existing brand details
$stmt = $con->prepare("SELECT brand_title FROM brands WHERE brand_id = ?");
$stmt->bind_param("i", $brand_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Brand not found'); window.location.href='view_brands.php';</script>";
    exit();
}

$brand = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['update_brand'])) {
    $new_title = trim($_POST['brand_title']);

    if (!empty($new_title)) {
        $update_stmt = $con->prepare("UPDATE brands SET brand_title = ? WHERE brand_id = ?");
        $update_stmt->bind_param("si", $new_title, $brand_id);
        if ($update_stmt->execute()) {
            echo "<script>alert('Brand updated successfully'); window.location.href='view_brands.php';</script>";
            exit();
        } else {
            $error = "Error updating brand.";
        }
    } else {
        $error = "Brand title cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Brand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Edit Brand</h2>

    <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST" class="w-50 mx-auto">
        <div class="mb-3">
            <label for="brand_title" class="form-label">Brand Title</label>
            <input type="text" name="brand_title" id="brand_title" class="form-control" value="<?php echo htmlspecialchars($brand['brand_title']); ?>" required>
        </div>
        <button type="submit" name="update_brand" class="btn btn-success">Update Brand</button>
        <a href="view_brands.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
