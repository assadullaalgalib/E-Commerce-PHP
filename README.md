# E-Commerce Website (PHP & MySQL)

## 📌 Project Overview

This is a fully functional e-commerce web application built using PHP (procedural) and MySQL/SQLite. Users can browse products, manage their cart, and checkout, while admins can manage products, categories, brands, orders, and users.

## 🚀 Features

### 👤 User Features

* User Registration & Login (with session management)
* Edit Profile & View Orders
* Product Browsing & Details
* Add to Cart & View Cart
* Checkout & Payment Processing
* Logout Functionality

### 🔑 Admin Features

* Secure Admin Login
* Manage Products (Add, Edit, Delete, View)
* Manage Categories & Brands
* View All Orders & Payments
* Manage Users

## 🗂 Project Structure

```
/Admin
   all_orders.php
   all_payments.php
   delete.php
   edit.php
   index.php
   insert_brands.php
   insert_category.php
   insert_product.php
   list_users.php
   view_brands.php
   view_categories.php
   view_product.php

/CSS
/Functions
   common_function.php
/Images
/Includes
   connect.php

/Users
   edit_profile.php
   login.php
   my_account.php
   register.php

Other Files:
cart.php
cart_view.php
checkout.php
display_all_products.php
index.php
logout.php
process_payment.php
product_details.php
README.md
.gitattributes
```

## ⚙️ Installation Guide

1. **Clone or Download this repository:**

```bash
git clone https://github.com/your-username/ecommerce-php.git
```

2. **Move Project Folder to your server directory:**

* XAMPP → `htdocs/`
* WAMP → `www/`

3. **Database Setup:**

* Import the SQL file (`database.sql`) into MySQL (or use `slnx.sqlite`).
* Update `/Includes/connect.php` with your DB credentials:

```php
$con = mysqli_connect("localhost", "root", "", "ecommerce_db");
```

4. **Run Project:**

* Start Apache & MySQL via XAMPP/WAMP.
* Open browser: `http://localhost/ecommerce-php/index.php`

## 🔒 Security

* Session-based authentication with timeout.
* Admin panel protected with role-based access.
* SQL Injection prevented using prepared statements.

## 👨‍💻 Technologies Used

* **Frontend:** HTML5, CSS3, Bootstrap, JavaScript
* **Backend:** PHP
* **Database:** MySQL or SQLite
* **Server:** Apache (XAMPP/WAMP)


