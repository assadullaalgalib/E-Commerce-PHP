<?php
session_start();
include("../Includes/connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT username, user_image FROM users WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$user_name = $user['username'] ?? $_SESSION['username'];
$user_image = "../images/" . ($user['user_image'] ?? "default_user.png");
if (!file_exists($user_image)) $user_image = "../images/default_user.png";

// KPIs
$total_products = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM products"))['count'];
$total_sold = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(quantity) AS total_sold FROM orders"))['total_sold'] ?? 0;
$total_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS count FROM users"))['count'];
$total_payments = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(p.product_price*o.quantity) AS total_payment FROM orders o JOIN products p ON o.product_id=p.product_id"))['total_payment'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="../images/logo.png" width="30" height="30" class="d-inline-block align-text-top me-2" alt="Logo">
      Admin Panel
    </a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link text-white d-flex align-items-center" href="../users/my_account.php?id=<?php echo $user_id; ?>">
          <img src="<?php echo htmlspecialchars($user_image); ?>" class="rounded-circle me-2" width="40" height="40" alt="Profile">
          <?php echo htmlspecialchars($user_name); ?>
        </a>
      </li>
    </ul>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 bg-dark text-white vh-100 sidebar py-4">
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
      <h3 class="text-center text-muted mb-4">Welcome, <?php echo htmlspecialchars($user_name); ?></h3>
      
      <div class="row g-4">
        <div class="col-md-3 col-sm-6">
          <a href="view_product.php" class="text-decoration-none">
            <div class="card text-center text-white bg-primary h-100 shadow-sm">
              <div class="card-body">
                <h5>Total Products</h5>
                <h3><?php echo $total_products; ?></h3>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-sm-6">
          <a href="all_orders.php" class="text-decoration-none">
            <div class="card text-center text-white bg-success h-100 shadow-sm">
              <div class="card-body">
                <h5>Total Sold</h5>
                <h3><?php echo $total_sold; ?></h3>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-sm-6">
          <a href="list_users.php" class="text-decoration-none">
            <div class="card text-center text-white bg-warning h-100 shadow-sm">
              <div class="card-body">
                <h5>Total Users</h5>
                <h3><?php echo $total_users; ?></h3>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-3 col-sm-6">
          <a href="all_payments.php" class="text-decoration-none">
            <div class="card text-center text-white bg-danger h-100 shadow-sm">
              <div class="card-body">
                <h5>Total Payments</h5>
                <h3>৳<?php echo number_format($total_payments, 2); ?></h3>
              </div>
            </div>
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
