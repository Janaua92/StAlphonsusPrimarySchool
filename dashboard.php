<?php
session_start();
require_once 'config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Role-based redirection
switch ($_SESSION['role']) {
    case 'admin':
        header("Location: admin/dashboard.php");
        break;
    case 'teacher':
        header("Location: teacher/dashboard.php");
        break;
    case 'teachingassistant':
        header("Location: assistant/dashboard.php");
        break;
    case 'pupils':
        header("Location: pupil/dashboard.php");
        break;
    default:
        // Invalid role - logout and redirect
        session_destroy();
        header("Location: login.php");
}
exit();
?>
