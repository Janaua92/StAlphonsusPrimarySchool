<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$ta_id = $_GET['id'] ?? 0;

// Check if TA exists
$ta = $conn->query("SELECT * FROM Teaching_Assistant WHERE TAssistant_ID = $ta_id")->fetch_assoc();
if (!$ta) {
    $_SESSION['error'] = "Teaching Assistant not found";
    header("Location: manage_teaching_assistants.php");
    exit();
}

// Delete TA
if ($conn->query("DELETE FROM Teaching_Assistant WHERE TAssistant_ID = $ta_id")) {
    $_SESSION['success'] = "Teaching Assistant deleted successfully";
} else {
    $_SESSION['error'] = "Error deleting teaching assistant: " . $conn->error;
}

header("Location: manage_teaching_assistants.php");
exit();
?>
