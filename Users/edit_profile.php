<?php
session_start();
include("../Includes/connect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user data including image
$stmt = $con->prepare("SELECT username, email, contact, user_image, password_hash FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Display message from session
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);

    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Verify current password
    if (!password_verify($current_password, $user['password_hash'])) {
        $_SESSION['message'] = "Current password is incorrect.";
        header("Location: edit_profile.php");
        exit();
    }

    // Password update logic
    $password_to_save = $user['password_hash'];
    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $_SESSION['message'] = "New passwords do not match.";
            header("Location: edit_profile.php");
            exit();
        }
        $password_to_save = password_hash($new_password, PASSWORD_DEFAULT);
    }

    // Handle image upload
    $image_path = $user['user_image']; // keep old image if not changed
    if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['user_image']['name'];
        $filetmp = $_FILES['user_image']['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = "profile_" . $user_id . "_" . time() . "." . $ext;
            $destination = "../Images/" . $new_filename;

            if (move_uploaded_file($filetmp, $destination)) {
                $image_path = $new_filename; // store only filename in DB
            } else {
                $_SESSION['message'] = "Failed to upload image.";
                header("Location: edit_profile.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Invalid image format. Only jpg, jpeg, png, gif allowed.";
            header("Location: edit_profile.php");
            exit();
        }
    }

    // Update user info in DB
    $update_stmt = $con->prepare("UPDATE users SET username=?, email=?, contact=?, user_image=?, password_hash=? WHERE user_id=?");
    $update_stmt->bind_param("sssssi", $username, $email, $contact, $image_path, $password_to_save, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['message'] = "Profile updated successfully.";
        $update_stmt->close();
        header("Location: my_account.php"); // Redirect after update
        exit();
    } else {
        $_SESSION['message'] = "Update failed, please try again.";
        header("Location: edit_profile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 20px; }
        form { background: #fff; padding: 20px; max-width: 500px; margin: auto; border-radius: 8px; }
        label { display: block; margin-top: 15px; }
        input[type="text"], input[type="email"], input[type="password"], input[type="file"] { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 20px; }
        .message { margin: 15px 0; color: green; }
        .error { color: red; }
        img.profile-img { max-width: 150px; margin-top: 10px; border-radius: 8px; display: block; }
    </style>
</head>
<body>

<h2>Edit Profile</h2>

<?php if ($message): ?>
    <p class="<?php echo strpos($message, 'successfully') !== false ? 'message' : 'error'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </p>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">

    <label>Profile Image</label>
    <?php
    $imagePath = "../Images/" . $user['user_image'];
    if (!empty($user['user_image']) && file_exists($imagePath)) {
        echo '<img src="' . htmlspecialchars($imagePath) . '" alt="Profile Image" class="profile-img" />';
    } else {
        echo '<p>No profile image uploaded.</p>';
    }
    ?>
    <input type="file" name="user_image" accept="image/*">
    <hr>

    <label>Username
        <input type="text" name="username" required value="<?php echo htmlspecialchars($user['username']); ?>">
    </label>

    <label>Email
        <input type="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
    </label>

    <label>Contact
        <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>">
    </label>

    <label>Current Password (required to save changes)
        <input type="password" name="current_password" required>
    </label>

    <label>New Password (leave blank if not changing)
        <input type="password" name="new_password">
    </label>

    <label>Confirm New Password
        <input type="password" name="confirm_password">
    </label>

    <button type="submit">Update Profile</button>
    <button type="button" onclick="window.location.href='index.php'">Cancel</button>
</form>

</body>
</html>
