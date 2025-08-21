<?php
session_start();
include('Includes/connect.php');
include('Functions/common_function.php');
include('cart.php');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>E-Commerce</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link rel="stylesheet" href="CSS/card.css" />
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="images/logo.png" width="40" height="40" class="me-2 rounded" /> E-Commerce
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="display_all_products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="Users/register.php">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="cart_view.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="cart_view.php">Total: ৳<?php echo get_cart_total_price(); ?></a></li>
      </ul>

      <form class="d-flex me-3" method="GET" action="index.php">
        <input class="form-control me-2" type="search" name="search" placeholder="Search..." 
          value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button class="btn btn-outline-light" type="submit">Search</button>
      </form>

      <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
          <?php if (isset($_SESSION['username'])): ?>
            <a class="nav-link text-warning" href="Users/my_account.php">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
          <?php else: ?>
            <span class="nav-link text-white">Welcome Guest</span>
          <?php endif; ?>
        </li>
        <?php if (isset($_SESSION['username'])): ?>
          <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-white" href="Users/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="bg-light py-5 text-center">
  <h1 class="display-4 fw-bold">Online Store</h1>
  <p class="lead text-secondary">Communication is at the heart of e-commerce and community.</p>
</div>

<!-- Main Content -->
<div class="container mt-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 d-none d-md-block">
      <div class="sidebar card p-3">
        <h5>Delivery Brands</h5>
        <ul class="list-unstyled"><?php get_brands(); ?></ul>
        <h5>Categories</h5>
        <ul class="list-unstyled"><?php get_categories(); ?></ul>
      </div>
    </div>

    <!-- Products -->
    <div class="col-md-10">
      <div class="d-flex flex-wrap justify-content-start">
        <?php 
        $products = get_products();
        if ($products) {
            foreach ($products as $product) {
                echo '
                <div class="card product-card m-2">
                    <img src="images/'.$product['image'].'" class="card-img-top" alt="'.$product['title'].'">
                    <div class="card-body">
                        <h5 class="card-title">'.$product['title'].'</h5>
                        <p class="card-text">Price: ৳'.$product['price'].'</p>
                        <a href="cart.php?add='.$product['id'].'" class="btn btn-primary w-100">Add to Cart</a>
                    </div>
                </div>
                ';
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center py-4 bg-dark text-white mt-5">
  <p class="mb-0">&copy; 2025 All rights reserved to Md Assadulla Al Galib.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
