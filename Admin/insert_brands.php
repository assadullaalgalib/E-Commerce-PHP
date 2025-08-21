<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../Includes/connect.php');

if (isset($_POST['insert_brand'])) {
    $brand_title = trim($_POST['brand_title']);
    
    if (empty($brand_title)) {
        $error = "Brand title cannot be empty.";
    } else {
        $select_query = "SELECT * FROM brands WHERE brand_title = ?";
        $stmt = mysqli_prepare($con, $select_query);
        mysqli_stmt_bind_param($stmt, "s", $brand_title);
        mysqli_stmt_execute($stmt);
        $result_select = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result_select) > 0) {
            $error = "This brand is already present in the database.";
        } else {
            $insert_query = "INSERT INTO brands (brand_title) VALUES (?)";
            $stmt_insert = mysqli_prepare($con, $insert_query);
            mysqli_stmt_bind_param($stmt_insert, "s", $brand_title);
            $result = mysqli_stmt_execute($stmt_insert);
            mysqli_stmt_close($stmt_insert);

            if ($result) {
                header("Location: insert_brands.php?msg=success");
                exit();
            } else {
                $error = "Failed to insert brand.";
            }
        }
        mysqli_stmt_close($stmt);
    }
}

$msg = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    $msg = "Brand has been inserted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Insert Brand</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Insert New Brand</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($msg)) : ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
                    <?php endif; ?>

                    <form action="insert_brands.php" method="post" novalidate>
                        <div class="mb-3">
                            <label for="brand_title" class="form-label">Brand Title</label>
                            <input type="text" id="brand_title" name="brand_title" class="form-control" placeholder="Enter brand name" required>
                        </div>
                        <div class="d-flex justify-content-start">
                            <button type="submit" name="insert_brand" class="btn btn-primary me-2"><i class="fas fa-plus"></i> Insert Brand</button>
                            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
