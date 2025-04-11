<?php
session_start();
require_once '../config.php';

// Verify admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get teacher ID from URL
$teacherId = $_GET['id'] ?? null;

if ($teacherId) {
    // First check if teacher has any assigned classes
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM Classes WHERE Teacher_ID = ?");
    $checkStmt->bind_param("i", $teacherId);
    $checkStmt->execute();
    $hasClasses = $checkStmt->get_result()->fetch_row()[0] > 0;
    
    if ($hasClasses) {
        $_SESSION['error'] = "Cannot delete teacher - they have classes assigned";
    } else {
        // Delete teacher
        $stmt = $conn->prepare("DELETE FROM Teachers WHERE Teacher_ID = ?");
        $stmt->bind_param("i", $teacherId);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Teacher deleted successfully";
        } else {
            $_SESSION['error'] = "Error deleting teacher: " . $conn->error;
        }
    }
}

header("Location: manage_teachers.php");
exit();
?>
