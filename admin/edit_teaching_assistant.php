<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Edit Teaching Assistant";
include '../includes/header.php';

$ta_id = $_GET['id'] ?? 0;
$ta = $conn->query("SELECT * FROM Teaching_Assistant WHERE TAssistant_ID = $ta_id")->fetch_assoc();

if (!$ta) {
    $_SESSION['error'] = "Teaching Assistant not found";
    header("Location: manage_teaching_assistants.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    
    $stmt = $conn->prepare("UPDATE Teaching_Assistant SET Name = ?, Email = ?, Username = ? WHERE TAssistant_ID = ?");
    $stmt->bind_param("sssi", $name, $email, $username, $ta_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Teaching Assistant updated successfully";
        header("Location: manage_teaching_assistants.php");
        exit();
    } else {
        $error = "Error updating teaching assistant: " . $conn->error;
    }
}
?>

<div class="container mt-4">
    <h1>Edit Teaching Assistant</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" 
                   value="<?= htmlspecialchars($ta['Name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?= htmlspecialchars($ta['Email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" 
                   value="<?= htmlspecialchars($ta['Username']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Teaching Assistant</button>
        <a href="manage_teaching_assistants.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
