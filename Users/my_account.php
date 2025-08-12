<?php
session_start();
include('../Includes/connect.php'); // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare SQL statement with correct column names
$sql = "SELECT username, email, contact, user_image 
        FROM users 
        WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
      function confirmDelete() {
          return confirm('Are you sure you want to delete your account? This action cannot be undone.');
      }
    </script>
</head>
<body class="container mt-5">

<h2>My Profile</h2>

<div class="card" style="width: 18rem;">
    <img src="../User_Images/<?php echo htmlspecialchars($user['user_image']); ?>" 
         class="card-img-top" alt="Profile Image">
    <div class="card-body">
        <h5 class="card-title"><?php echo htmlspecialchars($user['username']); ?></h5>
        <p class="card-text">
            <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?><br>
            <strong>Contact:</strong> <?php echo htmlspecialchars($user['contact']); ?>
        </p>
        <a href="edit_profile.php" class="btn btn-primary me-2">Edit Profile</a>
        <a href="delete_account.php" class="btn btn-danger" onclick="return confirmDelete();">Delete Account</a>
    </div>
</div>

</body>
</html>
