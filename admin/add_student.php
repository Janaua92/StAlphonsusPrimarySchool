<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Add Student";
include '../includes/header.php';

// Get available classes for dropdown
$classes = $conn->query("SELECT Class_ID, Name FROM Classes");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $age = (int)$_POST['age'];
        $address = $_POST['address'];
        $medical_info = $_POST['medical_info'] ?? null;
        $class_id = (int)$_POST['class_id'];

        $stmt = $conn->prepare("INSERT INTO Pupils 
                              (Pupils_ID, Name, Email, Username, Password, Age, Address, Medical_Info, Class_ID) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssissi", $pupil_id, $name, $email, $username, $password, $age, $address, $medical_info, $class_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Student added successfully";
            header("Location: manage_students.php");
            exit();
        } else {
            $error = "Error adding student: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h1>Add New Student</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="pupils_id" class="form-label">Student ID</label>
                    <input type="number" class="form-control" id="pupils_id" name="pupils_id" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age" min="5" max="18" required>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="medical_info" class="form-label">Medical Information</label>
                    <textarea class="form-control" id="medical_info" name="medical_info" rows="2"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="class_id" class="form-label">Class</label>
                    <select class="form-select" id="class_id" name="class_id" required>
                        <?php while($class = $classes->fetch_assoc()): ?>
                            <option value="<?= $class['Class_ID'] ?>">
                                <?= htmlspecialchars($class['Name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Add Student</button>
            <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
