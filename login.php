<?php
/**
 * Login Page for St Alphonsus School System
 * Handles authentication for all user roles (admin, teacher, teaching assistant, pupil)
 * Uses database authentication with fallback to hardcoded admin credentials
 * Implements basic security measures including password hashing verification
 */

session_start();
require 'config.php';

$error = ''; // Variable to store authentication error messages

// Hardcoded fallback credentials (for emergency access only)
// WARNING: In production, these should be removed or properly secured
$fallback_username = 'admin';
$fallback_password = 'admin123'; // Plaintext for simplicity; consider hashing in production
$fallback_role = 'admin';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user inputs
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Initialize variables for table and ID column based on role
    $table = '';      // Will store the database table name based on role
    $idColumn = '';   // Will store the ID column name based on role
    switch ($role) {
        case 'admin':
            $table = 'Admin';
            $idColumn = 'Admin_ID';
            break;
        case 'teacher':
            $table = 'Teacher';
            $idColumn = 'Teacher_ID';
            break;
        case 'teachingassistant':
            $table = 'Teaching_Assistant';
            $idColumn = 'TAssistant_ID';
            break;
        case 'pupils':
            $table = 'Pupils';
            $idColumn = 'Pupils_ID';
            break;
        default:
            // Handle invalid role selection
            $error = "Invalid role selected.";
    }

    // Proceed if no error
    if (!$error) {
        // Prepare a statement to check the database for the user
        $stmt = $conn->prepare("SELECT * FROM {$table} WHERE Username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = false;
        }

        // Verify user credentials if a matching record is found
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['Password'])) {
                // Set session variables for the logged-in user
                $_SESSION['user_id'] = $user[$idColumn];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = $role;
                $_SESSION['name'] = $user['Name'];

                // Redirect the user based on their role
                switch ($role) {
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
                }
                exit();
            } else {
                // Handle incorrect password
                $error = "Incorrect password.";
            }
        } else {
            // Fallback authentication (only if database lookup failed)
            // This provides emergency access but should be disabled in production
            if ($username === $fallback_username && $password === $fallback_password && $role === $fallback_role) {
                $_SESSION['user_id'] = 0; // Special ID indicating fallback user
                $_SESSION['username'] = $fallback_username;
                $_SESSION['role'] = $fallback_role;
                $_SESSION['name'] = 'Fallback Admin';

                header("Location: admin/dashboard.php");
                exit();
            } else {
                $error = "User not found or incorrect credentials.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - St Alphonsus School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 6rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>School Login</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" name="username" required placeholder="Enter username">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" required placeholder="Enter password">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Select Role:</label>
            <select class="form-select" name="role" required>
                <option value="">-- Choose Role --</option>
                <option value="admin">Admin</option>
                <option value="teacher">Teacher</option>
                <option value="teachingassistant">Teaching Assistant</option>
                <option value="pupils">Pupil</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

</body>
</html>
