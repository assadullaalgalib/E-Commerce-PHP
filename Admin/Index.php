<?php
session_start();
include("../Includes/connect.php"); // Correct path

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT username, email, contact, user_image 
        FROM users 
        WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// User name
$user_name = $user['username'] ?? $_SESSION['username'];

// User image
$user_image = "../images/" . ($user['user_image'] ?? "default_user.png");
if (!file_exists($user_image)) {
    $user_image = "../images/default_user.png"; // fallback
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="../images/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
      Admin Panel
    </a>
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a class="nav-link text-white" href="#">Welcome <?php echo htmlspecialchars($user_name); ?></a>
      </li>
    </ul>
  </div>
</nav>

<!-- Sidebar -->
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 bg-dark text-white py-4 sidebar">
      <div class="text-center mb-3">
        <img src="<?php echo htmlspecialchars($user_image); ?>" alt="User Profile" class="img-fluid rounded-circle mb-2" width="100">
        <h5><?php echo htmlspecialchars($user_name); ?></h5>
      </div>
      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="insert_product.php"><i class="fas fa-plus-circle me-2"></i> Insert Products</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="view_product.php"><i class="fas fa-box-open me-2"></i> View Products</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="insert_category.php"><i class="fas fa-plus-square me-2"></i> Insert Categories</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="view_categories.php"><i class="fas fa-list me-2"></i> View Categories</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="insert_brands.php"><i class="fas fa-tags me-2"></i> Insert Brands</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="view_brands.php"><i class="fas fa-copyright me-2"></i> View Brands</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="all_orders.php"><i class="fas fa-shopping-basket me-2"></i> All Orders</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="all_payments.php"><i class="fas fa-credit-card me-2"></i> All Payments</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="list_users.php"><i class="fas fa-users me-2"></i> List Users</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-4">
      <h4 class="text-center text-muted mt-5">Welcome to the Admin Dashboard</h4>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
