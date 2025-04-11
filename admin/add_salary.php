<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Add Salary";
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $salary_id = (int)$_POST['salary_id'];
        $amount = (float)$_POST['amount'];
        $role = $_POST['role'];

        $stmt = $conn->prepare("INSERT INTO Salary (Salary_ID, Annual_Amount, Role) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $salary_id, $amount, $role);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Salary added successfully";
            header("Location: manage_salaries.php");
            exit();
        } else {
            $error = "Error adding salary: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h1>Add New Salary</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="salary_id" class="form-label">Salary ID</label>
                    <input type="number" class="form-control" id="salary_id" name="salary_id" required>
                </div>
                
                <div class="mb-3">
                    <label for="amount" class="form-label">Annual Amount (£)</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="Teacher">Teacher</option>
                        <option value="Teaching Assistant">Teaching Assistant</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Add Salary</button>
            <a href="manage_salaries.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
