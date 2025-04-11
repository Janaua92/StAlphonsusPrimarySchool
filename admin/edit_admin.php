<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Edit Administrator";
include '../includes/header.php';

$admin_id = $_GET['id'] ?? 0;
$admin = $conn->query("SELECT * FROM Admin WHERE Admin_ID = $admin_id")->fetch_assoc();

if (!$admin) {
    $_SESSION['error'] = "Administrator not found";
    header("Location: manage_admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    
    $stmt = $conn->prepare("UPDATE Admin SET Name = ?, Email = ?, Username = ? WHERE Admin_ID = ?");
    $stmt->bind_param("sssi", $name, $email, $username, $admin_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Administrator updated successfully";
        header("Location: manage_admin.php");
        exit();
    } else {
        $error = "Error updating administrator: " . $conn->error;
    }
}
?>

<div class="container mt-4">
    <h1>Edit Administrator</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" 
                   value="<?= htmlspecialchars($admin['Name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?= htmlspecialchars($admin['Email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" 
                   value="<?= htmlspecialchars($admin['Username']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Administrator</button>
        <a href="manage_admin.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
