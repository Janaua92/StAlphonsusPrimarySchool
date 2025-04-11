<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Add Parent/Guardian";
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $parent_id = (int)$_POST['parent_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        $stmt = $conn->prepare("INSERT INTO `Parents/Guardians` 
                              (Parent_ID, Name, Email, Address, Phone_Number) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $parent_id, $name, $email, $address, $phone);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Parent/Guardian added successfully";
            header("Location: manage_parents.php");
            exit();
        } else {
            $error = "Error adding parent/guardian: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h1>Add New Parent/Guardian</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="parent_id" class="form-label">Parent ID</label>
                    <input type="number" class="form-control" id="parent_id" name="parent_id" required>
                </div>
                
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Add Parent/Guardian</button>
            <a href="manage_parents.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>