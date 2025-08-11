<?php
include('includes/connect.php');
$user_id = $_SESSION['user_id'];

$result = mysqli_query($con, "SELECT * FROM orders WHERE user_id='$user_id'");

echo "<h2>My Orders</h2>";

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='10'>
            <tr>
                <th>Order ID</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>".$row['order_id']."</td>
                <td>".$row['total_price']."</td>
                <td>".$row['status']."</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No orders found.</p>";
}
?>
