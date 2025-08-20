<?php
session_start();
include('../Includes/connect.php'); // Database connection

if (!isset($_SESSION['user_id'])) {
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
<body>

<div class="container">
    <h2 class="text-center mt-4">My Profile</h2>

    <div class="profile-card">
        <img src="<?php 
            if (!empty($user['user_image']) && file_exists(__DIR__ . '/../Images/' . $user['user_image'])) {
                echo '../Images/' . htmlspecialchars($user['user_image']);
            } else {
                echo 'https://via.placeholder.com/150?text=Profile';
            }
        ?>" alt="Profile Image">

        <h5><?php echo htmlspecialchars($user['username']); ?></h5>
        <p>
            <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?><br>
            <strong>Contact:</strong> <?php echo htmlspecialchars($user['contact']); ?>
        </p>

        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        <a href="delete_account.php" class="btn btn-danger" onclick="return confirmDelete();">Delete Account</a>
        <a href="../index.php" class="btn btn-secondary"> Back to Home</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
