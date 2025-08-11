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
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <!-- Navbar -->
    <div class="container-fluid p-0">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <img src="images/logo.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top me-2" />
          <a class="navbar-brand text-white fw-bold" href="index.php">E-Commerce</a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="display_all_products.php">Product</a></li>
              <li class="nav-item"><a class="nav-link" href="Users/register.php">Registrations</a></li>
              <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
              <li class="nav-item">
                  <a class="nav-link" href="cart_view.php"><i class="fas fa-shopping-cart"></i> Cart</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="cart_view.php">
                      Total Price: ৳<?php echo get_cart_total_price(); ?>
                  </a>
              </li>
            </ul>

            <form class="d-flex ms-3" role="search" method="GET" action="index.php">
              <input
                class="form-control me-2"
                name="search"
                type="search"
                placeholder="Search products..."
                aria-label="Search"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
              />
              <button class="btn btn-outline-light" type="submit">Search</button>
            </form>

            <!-- Right side of navbar: Welcome + Login/Logout -->
            <ul class="navbar-nav mb-2 mb-lg-0 ms-3">
              <li class="nav-item">
                <span class="navbar-text text-white me-3">
                  <?php
                    if (isset($_SESSION['username'])) {
                        echo "Welcome, " . htmlspecialchars($_SESSION['username']);
                    } else {
                        echo "Welcome Guest";
                    }
                  ?>
                </span>
              </li>
              <?php if (isset($_SESSION['username'])): ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
              <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="Users/login.php">Login</a></li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </nav>
    </div>

    <!-- Hero Section -->
    <div class="bg-light py-5">
      <div class="container text-center">
        <h1 class="display-4 text-dark fw-bold">Online Store</h1>
        <p class="lead text-secondary mt-3">Communication is at the heart of e-commerce and community.</p>
      </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-5">
      <div class="row">
        <!-- Sidebar for Brands and Categories -->
        <div class="col-md-2 d-none d-md-block">
          <div class="card p-3">
            <h5 class="card-title text-dark">Delivery Brands</h5>
            <ul class="list-unstyled mb-4">
              <?php get_brands(); ?>
            </ul>

            <h5 class="card-title text-dark">Categories</h5>
            <ul class="list-unstyled">
              <?php get_categories(); ?>
            </ul>
          </div>
        </div>

        <!-- Products Section -->
        <div class="col-md-10">
          <div class="row">
            <?php get_products(); ?>
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
