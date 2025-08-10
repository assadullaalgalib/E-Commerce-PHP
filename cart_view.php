<?php
session_start();
include('Includes/connect.php');
include('Functions/common_function.php'); // <-- include your common functions

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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shopping Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4 text-center">🛒 Your Cart</h2>

  <?php
  if (empty($_SESSION['cart'])) {
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
          $result = mysqli_query($con, "SELECT * FROM products WHERE product_id = $product_id");

          if ($row = mysqli_fetch_assoc($result)) {
              $title = esc($row['product_title']); // Using esc() from common_function.php
              $price = number_format($row['product_price'], 2);
              $subtotal = $row['product_price'] * $qty;
              $total += $subtotal;

              echo "<tr>
                      <td>{$title}</td>
                      <td>৳{$price}</td>
                      <td>
                        <input type='number' value='{$qty}' min='1' form='form{$product_id}' name='qty' class='form-control w-50 mx-auto'>
                      </td>
                      <td>৳" . number_format($subtotal, 2) . "</td>
                      <td>
                        <form method='post' id='form{$product_id}' class='d-flex justify-content-center gap-2'>
                          <input type='hidden' name='product_id' value='{$product_id}'>
                          <button type='submit' name='update_qty' class='btn btn-primary btn-sm'>Update</button>
                          <button type='submit' name='remove' class='btn btn-danger btn-sm'>Remove</button>
                        </form>
                      </td>
                    </tr>";
          }
      }

      echo "</tbody></table>";
      echo "<div class='text-end me-3'><h4>Total: ৳" . number_format($total, 2) . "</h4></div>";
      echo "<div class='text-center mt-4'>
              <a href='display_all_products.php' class='btn btn-secondary'>Continue Shopping</a>
              <a href='checkout.php' class='btn btn-success'>Checkout</a>
            </div>";
  }
  ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
