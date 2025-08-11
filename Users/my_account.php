<?php
session_start();

include("../Includes/connect.php"); // Corrected path

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Account</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }
        .account-container {
            display: flex;
            width: 90%;
            margin: 20px auto;
        }
        .account-sidebar {
            width: 250px;
            background: #343a40;
            padding: 20px;
            color: white;
            border-radius: 8px;
        }
        .account-sidebar h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .account-sidebar a {
            display: block;
            padding: 10px;
            margin: 8px 0;
            text-decoration: none;
            color: white;
            background: #495057;
            border-radius: 5px;
        }
        .account-sidebar a:hover {
            background: #17a2b8;
        }
        .account-content {
            flex: 1;
            background: white;
            padding: 20px;
            margin-left: 20px;
            border-radius: 8px;
            min-height: 400px;
        }
    </style>
</head>
<body>

<div class="account-container">
    <div class="account-sidebar">
        <h3>My Account</h3>
        <a href="my_account.php?section=edit_profile">Edit Profile</a>
        <a href="my_account.php?section=pending_orders">Pending Orders</a>
        <a href="my_account.php?section=my_orders">My Orders</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="account-content">
        <?php
        if (isset($_GET['section'])) {
            $section = $_GET['section'];

            if ($section == 'edit_profile') {
                include('user_sections/edit_profile.php');
            }
            elseif ($section == 'pending_orders') {
                include('user_sections/pending_orders.php');
            }
            elseif ($section == 'my_orders') {
                include('user_sections/my_orders.php');
            }
            else {
                echo "<h2>Welcome to your account!</h2><p>Select an option from the left menu.</p>";
            }
        } else {
            echo "<h2>Welcome to your account!</h2><p>Select an option from the left menu.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
