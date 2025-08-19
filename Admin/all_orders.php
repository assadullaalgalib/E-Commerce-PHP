<?php
//session_start();
include('../Includes/connect.php');

//Check if admin is logged in (optional)
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../users/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">All Orders</h2>
    <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Fetch all orders with user and product details
        $query = "
            SELECT o.order_id, u.username, p.product_title, o.quantity, o.order_date
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            JOIN products p ON o.product_id = p.product_id
            ORDER BY o.order_date DESC
        ";
        $result = mysqli_query($con, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['order_id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['product_title']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['order_date']}</td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
