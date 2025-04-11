<?php
session_start();
require_once '../config.php';

// Verify admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Edit Parent";
include '../includes/header.php';

// Get parent ID from URL
$parentId = $_GET['id'] ?? null;

if (!$parentId) {
    header("Location: manage_parents.php");
    exit();
}

// Fetch parent data
$stmt = $conn->prepare("SELECT * FROM Parents WHERE Parent_ID = ?");
$stmt->bind_param("i", $parentId);
$stmt->execute();
$parent = $stmt->get_result()->fetch_assoc();

if (!$parent) {
    header("Location: manage_parents.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update parent
    $stmt = $conn->prepare("UPDATE Parents SET Name=?, Email=?, Phone=?, Address=? WHERE Parent_ID=?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $parentId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Parent updated successfully";
        header("Location: manage_parents.php");
        exit();
    } else {
        $error = "Error updating parent: " . $conn->error;
    }
}
?>

<div class="container mt-4">
    <h1>Edit Parent</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($parent['Name']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($parent['Email']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($parent['Phone']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($parent['Address']); ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Parent</button>
        <a href="manage_parents.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
