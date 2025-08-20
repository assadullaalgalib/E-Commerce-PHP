<?php
session_start();
include('Includes/connect.php');
include('Functions/common_function.php'); // common functions

// Handle cart actions
if (isset($_POST['remove'])) {
    $remove_id = intval($_POST['product_id']);
    unset($_SESSION['cart'][$remove_id]);
    header("Location: cart_view.php");
    exit();
}

if (isset($_POST['update_qty'])) {
    $product_id = intval($_POST['product_id']);
    $new_qty = max(1, intval($_POST['qty']));
    $_SESSION['cart'][$product_id] = $new_qty;
    header("Location: cart_view.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">🛒 Your Cart</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <h4 class='text-center'>Your cart is empty.</h4>
        <div class='text-center mt-4'>
            <a href='index.php' class='btn btn-primary btn-lg'>Back to Home</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $product_id => $qty):
                        $result = mysqli_query($con, "SELECT * FROM products WHERE product_id = $product_id");
                        if ($row = mysqli_fetch_assoc($result)):
                            $title = esc($row['product_title']);
                            $price = number_format($row['product_price'], 2);
                            $subtotal = $row['product_price'] * $qty;
                            $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= $title ?></td>
                        <td>৳<?= $price ?></td>
                        <td>
                            <form method="post" class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <input type="number" name="qty" value="<?= $qty ?>" min="1" class="form-control w-50 mx-auto mx-sm-0">
                                <button type="submit" name="update_qty" class="btn btn-primary btn-sm mt-2 mt-sm-0">Update</button>
                                <button type="submit" name="remove" class="btn btn-danger btn-sm mt-2 mt-sm-0">Remove</button>
                            </form>
                        </td>
                        <td>৳<?= number_format($subtotal, 2) ?></td>
                        <td>
                            <a href="product_details.php?id=<?= $product_id ?>" class="btn btn-info btn-sm mb-2 mb-sm-0">View</a>
                        </td>
                    </tr>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>

        <div class="text-end me-3">
            <h4>Total: ৳<?= number_format($total, 2) ?></h4>
        </div>

        <div class="text-center mt-4 d-flex flex-column flex-sm-row justify-content-center gap-2">
            <a href="display_all_products.php" class="btn btn-primary">Continue Shopping</a>
            <a href="checkout.php" class="btn btn-success">Checkout</a>
            <a href="index.php" class="btn btn-secondary btn-lg mt-2 mt-sm-0">Back to Home</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
