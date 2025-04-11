<?php
session_start();
require_once '../config.php';

// Verify admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Add New Teacher";
include '../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $background_check = $_POST['background_check'];
        $salary_id = (int)$_POST['salary_id'];
        $class_id = (int)$_POST['class_id'];

        $stmt = $conn->prepare("INSERT INTO Teacher 
                              (Teacher_ID, Name, Email, Username, Password, Address, Phone_Number, Background_Check, Salary_ID, Class_ID) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssii", $teacher_id, $name, $email, $username, $password, $address, $phone, $background_check, $salary_id, $class_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Teacher added successfully";
            header("Location: manage_teachers.php");
            exit();
        } else {
            $error = "Error adding teacher: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Get available classes and salaries for dropdowns
$classes = $conn->query("SELECT Class_ID, Name FROM Classes");
$salaries = $conn->query("SELECT Salary_ID, Annual_Amount FROM Salary WHERE Role = 'Teacher'");
?>

<div class="container mt-4">
    <h1>Add New Teacher</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Teacher_ID</label>
                    <input type="number" class="form-control" id="teacher_id" name="teacher_id" required>
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
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                
                <div class="mb-3">
                    <label for="background_check" class="form-label">Background Check</label>
                    <select class="form-select" id="background_check" name="background_check" required>
                        <option value="Clear">Clear</option>
                        <option value="Pending">Pending</option>
                        <option value="Not Started">Not Started</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="salary_id" class="form-label">Salary</label>
                    <select class="form-select" id="salary_id" name="salary_id" required>
                        <?php while($salary = $salaries->fetch_assoc()): ?>
                            <option value="<?= $salary['Salary_ID'] ?>">
                                [<?= $salary['Salary_ID'] ?>] £<?= number_format($salary['Annual_Amount'], 2) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="class_id" class="form-label">Assigned Class</label>
                    <select class="form-select" id="class_id" name="class_id" required>
                        <option value="0">Not Assigned</option>
                        <?php while($class = $classes->fetch_assoc()): ?>
                            <option value="<?= $class['Class_ID'] ?>">
                                [<?= $class['Class_ID'] ?>] <?= htmlspecialchars($class['Name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Add Teacher</button>
            <a href="manage_teachers.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

