<?php
// DB connection (make sure the path is correct)
include(__DIR__ . '/../Includes/connect.php');

// Output sanitize helper
function esc($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Product card UI
function display_product_card($row) {
    $product_id = intval($row['product_id']);
    $product_title = htmlspecialchars($row['product_title'], ENT_QUOTES, 'UTF-8');
    $product_description = htmlspecialchars(substr($row['product_description'], 0, 100));
    $product_price = number_format($row['product_price'], 2);
    $product_image = htmlspecialchars($row['product_image1']);
    $image_path = "images/$product_image";

    echo "
        <div class='col-lg-4 col-md-6 mb-4'>
        <div class='card card-product shadow-sm border-0'>
            <img src='$image_path' class='card-img-top' alt='$product_title' />
            <div class='card-body d-flex flex-column'>
            <h5 class='card-title text-dark fw-bold'>$product_title</h5>
            <p class='card-text text-secondary'>$product_description...</p>
            <p class='fw-bold mt-auto text-dark'>Price: ৳$product_price</p>
            <div class='d-flex justify-content-between'>
                <a href='cart.php?add=$product_id' class='btn btn-primary text-white btn-sm'>Add to cart</a>
                <a href='product_details.php?id=$product_id' class='btn btn-outline-secondary btn-sm'>View More</a>
            </div>
            </div>
        </div>
        </div>";
            
        }

// Calculate total price in cart
function get_cart_total_price() {
    global $con;
    $total_price = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $query = "SELECT product_price FROM products WHERE product_id = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "i", $product_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $price);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if ($price) {
                $total_price += $price * $quantity;
            }
        }
    }
    return number_format($total_price, 2);
}

// Update quantities in cart
function update_cart_quantities($quantities) {
    foreach ($quantities as $product_id => $quantity) {
        $quantity = max(1, intval($quantity)); 
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Remove a product from cart
function remove_from_cart($product_id) {
    unset($_SESSION['cart'][$product_id]);
}

// Fetch and display products
function get_products() {
    global $con;

    $query = "SELECT * FROM products";
    $conditions = [];

    if (isset($_GET['category'])) {
        $category_id = intval($_GET['category']);
        $conditions[] = "category_id = $category_id";
    }

    if (isset($_GET['brand'])) {
        $brand_id = intval($_GET['brand']);
        $conditions[] = "brand_id = $brand_id";
    }

    if (isset($_GET['search'])) {
        $search_term = mysqli_real_escape_string($con, $_GET['search']);
        $conditions[] = "(product_title LIKE '%$search_term%' OR product_description LIKE '%$search_term%')";
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY product_id DESC LIMIT 12";

    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            display_product_card($row);
        }
    } else {
        echo "<p class='text-center text-danger'>No products found.</p>";
    }
}

// Show list of brands
function get_brands() {
    global $con;
    $query = "SELECT * FROM brands ORDER BY brand_title ASC";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $brand_id = intval($row['brand_id']);
            $brand_title = esc($row['brand_title']);
            echo "<li><a href='index.php?brand=$brand_id' class='text-decoration-none text-secondary'>$brand_title</a></li>";
        }
    } else {
        echo "<li class='text-muted'>No brands available</li>";
    }
}

// Show list of categories
function get_categories() {
    global $con;
    $query = "SELECT * FROM categories ORDER BY category_title ASC";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $category_id = intval($row['category_id']);
            $category_title = esc($row['category_title']);
            echo "<li><a href='index.php?category=$category_id' class='text-decoration-none text-secondary'>$category_title</a></li>";
        }
    } else {
        echo "<li class='text-muted'>No categories available</li>";
    }
}
?>
