<?php
session_start();
include('../Includes/connect.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user'; // 'admin' or 'user'

// Fetch user data
$sql = "SELECT username, email, contact, user_image FROM users WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Account</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../CSS/style.css">
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete your account? This action cannot be undone.');
    }
</script>
</head>
<body class="bg-light">

<div class="container">
    <h2 class="text-center mt-4">My Profile</h2>

    <div class="profile-card shadow-sm rounded">
        <img src="<?php 
            $image_path = '../Images/' . ($user['user_image'] ?? 'default_user.png');
            echo file_exists($image_path) ? $image_path : '../Images/default_user.png';
        ?>" alt="Profile Image" class="profile-img">

        <h5 class="username">
            <a href="profile.php" class="text-decoration-none">
                <?php echo htmlspecialchars($user['username']); ?>
            </a>
        </h5>

        <p class="user-info">
            <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?><br>
            <strong>Contact:</strong> <?php echo htmlspecialchars($user['contact']); ?>
        </p>

        <div class="profile-actions">
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            <a href="delete_account.php" class="btn btn-danger" onclick="return confirmDelete();">Delete Account</a>
            <?php if($role === 'admin'): ?>
                <a href="../admin/index.php" class="btn btn-secondary">Admin Dashboard</a>
            <?php else: ?>
                <a href="../index.php" class="btn btn-secondary">Back to Home</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
