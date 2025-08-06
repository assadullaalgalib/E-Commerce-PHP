<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Custom CSS -->
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
        <a class="nav-link text-white" href="#">Welcome Guest</a>
      </li>
    </ul>
  </div>
</nav>

<!-- Header -->
<h3 class="text-center text-dark bg-light py-2">Manage Details</h3>

<!-- Dashboard Layout -->
<div class="container-fluid">
  <div class="row">
    
    <!-- Sidebar -->
    <div class="col-md-2 bg-dark text-white py-4 sidebar">
      <div class="text-center mb-3">
        <img src="../images/admin_image.jpg" alt="Admin Profile" class="img-fluid rounded-circle mb-2" width="100">
        <h5>Admin Name</h5>
      </div>
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link text-white" href="insert_product.php">
            <i class="fas fa-plus-circle me-2"></i> Insert Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="view_product.php">
            <i class="fas fa-box-open me-2"></i> View Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="insert_category.php">
            <i class="fas fa-plus-square me-2"></i> Insert Categories
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="view_categories.php">
            <i class="fas fa-list me-2"></i> View Categories
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="insert_brands.php">
            <i class="fas fa-tags me-2"></i> Insert Brands
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="view_brands.php">
            <i class="fas fa-copyright me-2"></i> View Brands
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="all_orders.php">
            <i class="fas fa-shopping-basket me-2"></i> All Orders
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="all_payments.php">
            <i class="fas fa-credit-card me-2"></i> All Payments
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="list_users.php">
            <i class="fas fa-users me-2"></i> List Users
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="logout.php">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content Area -->
    <div class="col-md-10 p-4">
      <?php
        if (isset($_GET['insert_category'])) {
            include('insert_category.php');
        } elseif (isset($_GET['insert_brands'])) {
            include('insert_brands.php');
        } else {
            echo '<h4 class="text-center text-muted mt-5">Welcome to the Admin Dashboard</h4>';
        }
      ?>
    </div>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
