<?php
session_start();
require_once '../config.php';

// Verify admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get student ID from URL
$studentId = $_GET['id'] ?? null;

if ($studentId) {
    // Delete student
    $stmt = $conn->prepare("DELETE FROM Pupils WHERE Pupils_ID = ?");
    $stmt->bind_param("i", $studentId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Student deleted successfully";
    } else {
        $_SESSION['error'] = "Error deleting student: " . $conn->error;
    }
}

header("Location: manage_students.php");
exit();
?>
