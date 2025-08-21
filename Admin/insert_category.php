<?php
session_start();
include('../Includes/connect.php');

if (isset($_POST['insert_category'])) {
    $category_title = trim($_POST['cat_title']);
    
    if (empty($category_title)) {
        $error = "Category title cannot be empty.";
    } else {
        $select_query = "SELECT * FROM categories WHERE category_title = ?";
        $stmt = mysqli_prepare($con, $select_query);
        mysqli_stmt_bind_param($stmt, "s", $category_title);
        mysqli_stmt_execute($stmt);
        $result_select = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result_select) > 0) {
            $error = "This category is already present in the database.";
        } else {
            $insert_query = "INSERT INTO categories (category_title) VALUES (?)";
            $stmt_insert = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($stmt_insert, "s", $category_title);
            $result = mysqli_stmt_execute($stmt_insert);
            mysqli_stmt_close($stmt_insert);

            if ($result) {
                header("Location: insert_category.php?msg=success");
                exit();
            } else {
                $error = "Failed to insert category.";
            }
        }
        mysqli_stmt_close($stmt);
    }
}

$msg = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $msg = "Category has been inserted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Insert Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container my-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0"><i class="fas fa-receipt me-2"></i>Insert New Category</h3>
        </div>
        <div class="card-body">

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($msg)) : ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <form action="insert_category.php" method="post" novalidate>
                <div class="mb-3">
                    <label for="cat_title" class="form-label">Category Title</label>
                    <input type="text" id="cat_title" name="cat_title" class="form-control" placeholder="Enter Category Title" required>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" name="insert_category" class="btn btn-info me-3">Insert Category</button>
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
