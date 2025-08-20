<?php
session_start();
include('Includes/connect.php');
include('Functions/common_function.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>All Products</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4 text-center">All Products</h2>

    <!-- Search Form -->
    <form class="d-flex justify-content-center mb-4" method="GET" action="">
        <input class="form-control w-50 me-2" type="search" name="search" placeholder="Search products..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button class="btn btn-outline-dark" type="submit">Search</button>
    </form>

    <!-- Products Row -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php
        $query = "SELECT * FROM products";
        if (!empty($_GET['search'])) {
            $search_term = mysqli_real_escape_string($con, $_GET['search']);
            $query .= " WHERE product_title LIKE '%$search_term%' OR product_description LIKE '%$search_term%'";
        }
        $query .= " ORDER BY product_id DESC";

        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (empty($row['product_image1'])) $row['product_image1'] = 'default.png';
        ?>
        <div class="col">
            <div class="card h-100">
                <img src="images/<?php echo htmlspecialchars($row['product_image1']); ?>" 
                     class="card-img-top" alt="<?php echo htmlspecialchars($row['product_title']); ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['product_title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($row['product_description']); ?></p>
                    <p class="fw-bold mt-auto">Price: ৳<?php echo number_format($row['product_price'], 2); ?></p>
                    <div class="d-flex justify-content-between mt-2">
                        <a href="cart.php?add=<?php echo $row['product_id']; ?>" class="btn btn-primary btn-sm">Add to cart</a>
                        <a href="product_details.php?id=<?php echo $row['product_id']; ?>" class="btn btn-outline-secondary btn-sm">View More</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<p class='text-center text-danger'>No products found.</p>";
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
