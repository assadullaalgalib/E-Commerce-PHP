<?php
session_start();
include('../Includes/connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../users/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="text-primary mb-4">All Payments</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "
                SELECT o.order_id, u.username, p.product_title, o.quantity, o.order_date, p.product_price
                FROM orders o
                JOIN users u ON o.user_id = u.user_id
                JOIN products p ON o.product_id = p.product_id
                ORDER BY o.order_date DESC
            ";
            $result = mysqli_query($con, $query);

            $grand_total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $total_price = $row['product_price'] * $row['quantity'];
                $grand_total += $total_price;

                echo "<tr>
                        <td>{$row['order_id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['product_title']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['order_date']}</td>
                        <td>৳{$total_price}</td>
                        <td>
                            <a href='delete_order.php?id={$row['order_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this order?\");'>
                                <i class='fas fa-trash-alt'></i> Delete
                            </a>
                        </td>
                      </tr>";
            }
            ?>
            <tr class="table-secondary">
                <td colspan="5" class="text-end fw-bold">Grand Total</td>
                <td colspan="2" class="fw-bold">৳<?php echo number_format($grand_total, 2); ?></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-success"><i class="fas fa-home me-1"></i> Back to Home</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>
