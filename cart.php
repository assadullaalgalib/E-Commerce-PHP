<?php
 if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);

    // If already in cart, increment quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += 1;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    // Redirect back to previous page OR to cart_view.php
    header("Location: cart_view.php");
    exit();
}
?>
