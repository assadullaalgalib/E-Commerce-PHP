<?php
session_start();
include('Includes/connect.php');
include('Functions/common_function.php'); 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid product ID.";
    exit;
}

$product_id = intval($_GET['id']);

// Fetch product details
$query = "SELECT p.*, c.category_title, b.brand_title 
          FROM products p
          LEFT JOIN categories c ON p.category_id = c.category_id
          LEFT JOIN brands b ON p.brand_id = b.brand_id
          WHERE p.product_id = $product_id LIMIT 1";

$result = mysqli_query($con, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Product not found.";
    exit;
}

$product = mysqli_fetch_assoc($result);

// Handle form submission to add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $qty = intval($_POST['quantity']);
    if ($qty < 1) $qty = 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }

    // Redirect to cart view page after adding
    header('Location: cart_view.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo esc($product['product_title']); ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4"><?php echo esc($product['product_title']); ?></h2>
    <div class="row">
        <div class="col-md-6">
            <img src="images/<?php echo esc($product['product_image1']); ?>" alt="<?php echo esc($product['product_title']); ?>" class="img-fluid mb-3" />
            <?php if(!empty($product['product_image2'])): ?>
                <img src="images/<?php echo esc($product['product_image2']); ?>" alt="<?php echo esc($product['product_title']); ?>" class="img-fluid mb-3" />
            <?php endif; ?>
            <?php if(!empty($product['product_image3'])): ?>
                <img src="images/<?php echo esc($product['product_image3']); ?>" alt="<?php echo esc($product['product_title']); ?>" class="img-fluid mb-3" />
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h4>Price: ৳<?php echo number_format($product['product_price'], 2); ?></h4>
            <p><strong>Category:</strong> <?php echo esc($product['category_title']); ?></p>
            <p><strong>Brand:</strong> <?php echo esc($product['brand_title']); ?></p>
            <p><strong>Description:</strong><br /><?php echo nl2br(esc($product['product_description'])); ?></p>
            <p><strong>Keywords:</strong> <?php echo esc($product['product_keywords']); ?></p>

            <!-- Add to Cart Form -->
            <form method="post" class="mt-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control mb-3" style="width:100px;" />
                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
            </form>

            <a href="index.php" class="btn btn-secondary mt-3">Back to Products</a>
        </div>
    </div>

    <!-- Other products section -->
    <hr class="my-5">

    <h3>Other Products</h3>
    <div class="row">
        <?php
        // Fetch all other products (limit 6)
        $query_all = "SELECT * FROM products WHERE product_id != $product_id ORDER BY product_id DESC LIMIT 6";
        $result_all = mysqli_query($con, $query_all);

        if ($result_all && mysqli_num_rows($result_all) > 0) {
            while ($prod = mysqli_fetch_assoc($result_all)) {
                $pid = intval($prod['product_id']);
                $ptitle = esc($prod['product_title']);
                $pimage = esc($prod['product_image1']);
                $pprice = number_format($prod['product_price'], 2);
                ?>
                <div class="col-md-2 mb-4">
                    <div class="card h-100">
                        <a href="product_details.php?id=<?= $pid ?>">
                            <img src="images/<?= $pimage ?>" alt="<?= $ptitle ?>" class="card-img-top" style="height: 120px; object-fit: cover;">
                        </a>
                        <div class="card-body p-2">
                            <h6 class="card-title" style="font-size: 14px;"><?= $ptitle ?></h6>
                            <p class="text-success mb-0" style="font-size: 13px;">৳<?= $pprice ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No other products found.</p>";
        }
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
