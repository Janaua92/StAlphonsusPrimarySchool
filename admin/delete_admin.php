<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$admin_id = $_GET['id'] ?? 0;

// Check if admin exists
$admin = $conn->query("SELECT * FROM Admin WHERE Admin_ID = $admin_id")->fetch_assoc();
if (!$admin) {
    $_SESSION['error'] = "Administrator not found";
    header("Location: manage_admin.php");
    exit();
}

// Prevent deleting own account
if ($admin_id == $_SESSION['user_id']) {
    $_SESSION['error'] = "You cannot delete your own account";
    header("Location: manage_admin.php");
    exit();
}

// Delete admin
if ($conn->query("DELETE FROM Admin WHERE Admin_ID = $admin_id")) {
    $_SESSION['success'] = "Administrator deleted successfully";
} else {
    $_SESSION['error'] = "Error deleting administrator: " . $conn->error;
}

header("Location: manage_admin.php");
exit();
?>
