<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Add Class";
include '../includes/header.php';

// Get available teachers for dropdown
$teachers = $conn->query("SELECT Teacher_ID, Name FROM Teacher");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $class_id = (int)$_POST['class_id'];
        $name = $_POST['name'];
        $capacity = (int)$_POST['capacity'];
        $teacher_id = (int)$_POST['teacher_id'];

        $stmt = $conn->prepare("INSERT INTO Classes (Class_ID, Name, Capacity, Teacher_ID) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isii", $class_id, $name, $capacity, $teacher_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Class added successfully";
            header("Location: manage_classes.php");
            exit();
        } else {
            $error = "Error adding class: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h1>Add New Class</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="class_id" class="form-label">Class ID</label>
            <input type="number" class="form-control" id="class_id" name="class_id" required>
        </div>
        
        <div class="mb-3">
            <label for="name" class="form-label">Class Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" class="form-control" id="capacity" name="capacity" required>
        </div>
        
        <div class="mb-3">
            <label for="teacher_id" class="form-label">Assign Teacher</label>
            <select class="form-select" id="teacher_id" name="teacher_id" required>
                <?php while($teacher = $teachers->fetch_assoc()): ?>
                    <option value="<?= $teacher['Teacher_ID'] ?>">
                        [<?= $teacher['Teacher_ID'] ?>] <?= htmlspecialchars($teacher['Name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Add Class</button>
            <a href="manage_classes.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
