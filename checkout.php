<?php
session_start();
include('Includes/connect.php');
include('Functions/common_function.php');
// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty. Please add products before checkout.'); window.location.href='cart_view.php';</script>";
    exit();
}

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    $current_page = basename($_SERVER['PHP_SELF']);
    header("Location: http://localhost/E-Commerce-PHP/Users/login.php?redirect_to=$current_page");
    echo "<script>alert('Please log in to proceed with checkout.');</script>";
    exit();
}

// User is logged in, show checkout page or payment form
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Checkout - Payment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">Checkout - Payment</h2>

  <?php
  // Calculate total price from cart
  $total_price = 0;
  foreach ($_SESSION['cart'] as $product_id => $qty) {
      $stmt = mysqli_prepare($con, "SELECT product_title, product_price FROM products WHERE product_id = ?");
      mysqli_stmt_bind_param($stmt, "i", $product_id);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $title, $price);
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);
      $total_price += $price * $qty;
  }
  ?>

  <p><strong>Total to pay:</strong> ৳<?php echo number_format($total_price, 2); ?></p>

  <!-- Example payment form -->
  <form action="process_payment.php" method="POST">
    <div class="mb-3">
      <label for="card_number" class="form-label">Card Number</label>
      <input type="text" name="card_number" id="card_number" class="form-control" placeholder="1234 5678 9012 3456" required>
    </div>
    <div class="mb-3">
      <label for="expiry_date" class="form-label">Expiry Date</label>
      <input type="text" name="expiry_date" id="expiry_date" class="form-control" placeholder="MM/YY" required>
    </div>
    <div class="mb-3">
      <label for="cvv" class="form-label">CVV</label>
      <input type="password" name="cvv" id="cvv" class="form-control" placeholder="123" required>
    </div>

    <button type="submit" class="btn btn-success">Pay ৳<?php echo number_format($total_price, 2); ?></button>
  </form>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
