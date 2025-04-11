<?php
session_start();
require_once '../config.php';

// Verify admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get class ID from URL
$classId = $_GET['id'] ?? null;

if ($classId) {
    // First check if class has any students
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM Pupils WHERE Class_ID = ?");
    $checkStmt->bind_param("i", $classId);
    $checkStmt->execute();
    $hasStudents = $checkStmt->get_result()->fetch_row()[0] > 0;
    
    if ($hasStudents) {
        $_SESSION['error'] = "Cannot delete class - it has students assigned";
    } else {
        // Delete class
        $stmt = $conn->prepare("DELETE FROM Classes WHERE Class_ID = ?");
        $stmt->bind_param("i", $classId);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Class deleted successfully";
        } else {
            $_SESSION['error'] = "Error deleting class: " . $conn->error;
        }
    }
}

header("Location: manage_classes.php");
exit();
?>
