<?php
session_start();
include('../Includes/connect.php');

if (!isset($_GET['id'])) {
    header("Location: all_users.php");
    exit();
}

$user_id = $_GET['id'];

// Fetch user data
$stmt = $con->prepare("SELECT username, email, contact, user_image FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $contact  = trim($_POST['contact']);

    // Handle image upload
    $image_path = $user['user_image']; // keep old image
    if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['user_image']['name'];
        $tmp_file = $_FILES['user_image']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = "profile_" . $user_id . "_" . time() . "." . $ext;
            $destination = "../Images/" . $new_filename;

            if (move_uploaded_file($tmp_file, $destination)) {
                // Delete old image if exists
                if (!empty($user['user_image']) && file_exists("../Images/" . $user['user_image'])) {
                    unlink("../Images/" . $user['user_image']);
                }
                $image_path = $new_filename;
            }
        }
    }

    // Update database
    $stmt = $con->prepare("UPDATE users SET username=?, email=?, contact=?, user_image=? WHERE user_id=?");
    $stmt->bind_param("ssssi", $username, $email, $contact, $image_path, $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: all_users.php?msg=updated"); // redirect after update
        exit();
    } else {
        $stmt->close();
        echo "<p style='color:red;'>Update failed!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; }
    </style>
</head>
<body class="container mt-5">
<h2>Edit User</h2>

<form method="POST" action="" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Profile Image :</label><br>
        <?php
        $imagePath = "../Images/" . $user['user_image'];
        if (!empty($user['user_image']) && file_exists($imagePath)) {
            echo '<img src="' . htmlspecialchars($imagePath) . '" class="profile-img" alt="Profile">';
        } else {
            echo '<p>No image uploaded</p>';
        }
        ?>
        <input type="file" name="user_image" class="form-control mt-2" accept="image/*">
    </div>

    <div class="mb-3">
        <label>Username :</label>
        <input type="text" name="username" class="form-control" required value="<?php echo htmlspecialchars($user['username']); ?>">
    </div>

    <div class="mb-3">
        <label>Email :</label>
        <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($user['email']); ?>">
    </div>

    <div class="mb-3">
        <label>Contact :</label>
        <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($user['contact']); ?>">
    </div>

    <button type="submit" class="btn btn-success">Update User</button>
    <a href="list_users.php" class="btn btn-secondary">Cancel</a>
</form>
</body>
</html>
