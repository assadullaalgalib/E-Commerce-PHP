<?php
session_start();
include('Includes/connect.php');

// Remove item from cart if "remove" is clicked
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header("Location: cart_view.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Shopping Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4 text-center">🛒 Your Cart</h2>

  <?php
  if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
      echo "<h4 class='text-center'>Your cart is empty.</h4>";
  } else {
      echo "<table class='table table-bordered text-center'>";
      echo "<thead class='table-dark'>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
              </tr>
            </thead><tbody>";

      $total = 0;

      foreach ($_SESSION['cart'] as $product_id => $qty) {
          $get_product = "SELECT * FROM products WHERE product_id = $product_id";
          $result = mysqli_query($con, $get_product);

          if ($row = mysqli_fetch_assoc($result)) {
              $title = $row['product_title'];
              $price = $row['product_price'];
              $subtotal = $price * $qty;
              $total += $subtotal;

              echo "<tr>
                      <td>$title</td>
                      <td>\$$price</td>
                      <td>$qty</td>
                      <td>\$$subtotal</td>
                      <td>
                        <a href='cart_view.php?remove=$product_id' class='btn btn-danger btn-sm'>Remove</a>
                      </td>
                    </tr>";
          }
      }

      echo "</tbody></table>";
      echo "<div class='text-end me-3'><h4>Total: \$$total</h4></div>";
      echo "<div class='text-center mt-4'>
              <a href='display_all_products.php' class='btn btn-secondary'>Continue Shopping</a>
              <a href='#' class='btn btn-success'>Checkout</a>
            </div>";
  }
  ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
