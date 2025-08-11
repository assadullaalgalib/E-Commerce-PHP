<?php
session_start();
include('Includes/connect.php');
include('Functions/common_function.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty!'); window.location.href='cart_view.php';</script>";
    exit();
}

// Here you'd process payment info (card number, expiry, cvv) securely with payment gateway
// For demo, we just simulate success

// After successful payment, clear cart
unset($_SESSION['cart']);

// Show success message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
    <h2>Payment Successful</h2>
    <p>Thank you for your purchase! Your order has been placed successfully.</p>
    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
</div>
</body>
</html>
