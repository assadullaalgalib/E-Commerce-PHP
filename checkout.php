<?php
session_start();
include('Includes/connect.php');
include('Functions/common_function.php');

// Initialize errors array
$errors = [];

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty. Please add products before checkout.'); window.location.href='cart_view.php';</script>";
    exit();
}

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    $current_page = basename($_SERVER['PHP_SELF']);
    header("Location: Users/login.php?redirect_to=$current_page");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_number = trim($_POST['card_number']);
    $expiry_date = trim($_POST['expiry_date']);
    $cvv = trim($_POST['cvv']);

    // Basic server-side validation
    if (empty($card_number) || !preg_match('/^\d{13,19}$/', str_replace(' ', '', $card_number))) {
        $errors[] = "Please enter a valid card number (13-19 digits).";
    }
    if (empty($expiry_date) || !preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry_date)) {
        $errors[] = "Please enter a valid expiry date (MM/YY).";
    }
    if (empty($cvv) || !preg_match('/^\d{3,4}$/', $cvv)) {
        $errors[] = "Please enter a valid CVV (3 or 4 digits).";
    }

    // If no errors, proceed with payment logic
    if (empty($errors)) {
        // Example: redirect to process payment page
        header("Location: process_payment.php");
        exit();
    }
}

// Calculate total price
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

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - Payment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<script>
  // Client-side validation
  function validateForm() {
      let card = document.getElementById('card_number').value.trim();
      let expiry = document.getElementById('expiry_date').value.trim();
      let cvv = document.getElementById('cvv').value.trim();
      let errors = [];

      if (!/^\d{13,19}$/.test(card.replace(/\s+/g, ''))) {
          errors.push("Invalid card number");
      }
      if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry)) {
          errors.push("Invalid expiry date");
      }
      if (!/^\d{3,4}$/.test(cvv)) {
          errors.push("Invalid CVV");
      }

      if (errors.length > 0) {
          alert(errors.join("\\n"));
          return false;
      }
      return true;
  }
</script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Checkout - Payment</h2>

    <?php if (!empty($errors)): ?>
        <div class="row justify-content-center mb-3">
            <div class="col-md-6">
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error) echo "<p class='mb-1'>$error</p>"; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <p class="fw-bold">Total to pay: ৳<?php echo number_format($total_price, 2); ?></p>

            <form action="" method="POST" onsubmit="return validateForm();">
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
                <button type="submit" class="btn btn-success w-100">Pay ৳<?php echo number_format($total_price, 2); ?></button>
            </form>

            <div class="text-center mt-3">
                <a href="cart_view.php" class="btn btn-secondary">Back to Cart</a>
                <a href="index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
