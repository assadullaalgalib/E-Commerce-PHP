<?php
session_start();
include('Includes/connect.php');

// Check cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty.'); window.location.href='cart_view.php';</script>";
    exit();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    $current_page = basename($_SERVER['PHP_SELF']);
    header("Location: Users/login.php?redirect_to=$current_page");
    exit();
}

// Calculate total
$total_price = 0;
foreach ($_SESSION['cart'] as $product_id => $qty) {
    $stmt = mysqli_prepare($con, "SELECT product_price FROM products WHERE product_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $price);
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
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4 text-center">Checkout - Payment</h2>

    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card p-4 shadow-sm">
                <p><strong>Total to pay:</strong> ৳<?php echo number_format($total_price,2); ?></p>

                <form id="checkoutForm" action="process_payment.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="card_number" class="form-label">Card Number</label>
                        <input type="text" name="card_number" id="card_number" class="form-control" placeholder="1234 5678 9012 3456" required>
                        <div class="invalid-feedback">Please enter a valid card number.</div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-6">
                            <label for="expiry_date" class="form-label">Expiry Date</label>
                            <input type="text" name="expiry_date" id="expiry_date" class="form-control" placeholder="MM/YY" required>
                            <div class="invalid-feedback">Enter expiry date.</div>
                        </div>
                        <div class="col-6">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="password" name="cvv" id="cvv" class="form-control" placeholder="123" required>
                            <div class="invalid-feedback">Enter CVV.</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-3">Pay ৳<?php echo number_format($total_price,2); ?></button>
                </form>

                <a href="cart_view.php" class="btn btn-secondary w-100 mt-2">Back to Cart</a>
            </div>
        </div>
    </div>
</div>

<script>
// Bootstrap validation
(function () {
  'use strict'
  var forms = document.querySelectorAll('#checkoutForm')
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
