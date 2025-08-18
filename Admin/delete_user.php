<?php
session_start();
include('../Includes/connect.php');

// Optional: Check if admin is logged in
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit();
// }

if (!isset($_GET['id'])) {
    header("Location: all_users.php");
    exit();
}

$user_id = intval($_GET['id']);

// Fetch the user to get the image filename
$stmt = $con->prepare("SELECT user_image FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Delete image file if exists
if (!empty($user['user_image']) && file_exists("../Images/" . $user['user_image'])) {
    unlink("../Images/" . $user['user_image']);
}

// Delete user from database
$delete_stmt = $con->prepare("DELETE FROM users WHERE user_id = ?");
$delete_stmt->bind_param("i", $user_id);

if ($delete_stmt->execute()) {
    $delete_stmt->close();
    header("Location: list_users.php?msg=deleted");
    exit();
} else {
    $delete_stmt->close();
    echo "Failed to delete user. Please try again.";
}
?>
