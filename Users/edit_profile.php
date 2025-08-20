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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="mb-4 text-center">Edit Profile</h3>

          <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?>">
              <?php echo htmlspecialchars($message); ?>
            </div>
          <?php endif; ?>

          <form method="POST" enctype="multipart/form-data">

            <!-- Profile Image -->
            <div class="mb-3 text-center">
              <?php
              $imagePath = "../Images/" . $user['user_image'];
              if (!empty($user['user_image']) && file_exists($imagePath)) {
                  echo '<img src="' . htmlspecialchars($imagePath) . '" alt="Profile Image" class="img-thumbnail rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">';
              } else {
                  echo '<div class="text-muted mb-3">No profile image uploaded</div>';
              }
              ?>
              <input type="file" name="user_image" class="form-control" accept="image/*">
            </div>

            <!-- Username -->
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" required value="<?php echo htmlspecialchars($user['username']); ?>">
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>

            <!-- Contact -->
            <div class="mb-3">
              <label class="form-label">Contact</label>
              <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($user['contact']); ?>">
            </div>

            <!-- Current Password -->
            <div class="mb-3">
              <label class="form-label">Current Password <span class="text-danger">*</span></label>
              <input type="password" name="current_password" class="form-control" required>
            </div>

            <!-- New Password -->
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <input type="password" name="new_password" class="form-control">
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
              <label class="form-label">Confirm New Password</label>
              <input type="password" name="confirm_password" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-primary">Update Profile</button>
              <a href="my_account.php" class="btn btn-secondary">Cancel</a>

                <div class="text-center mt-3">
                    <a href="javascript:history.back()" class="btn btn-primary">Back</a>
                </div>
            </div>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
