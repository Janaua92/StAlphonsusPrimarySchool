<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Add Administrator";
include '../includes/header.php';

// Get available admin salaries
$salaries = $conn->query("SELECT Salary_ID, Annual_Amount FROM Salary WHERE Role = 'Admin'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $background_check = $_POST['background_check'];
        $salary_id = (int)$_POST['salary_id'];

        $stmt = $conn->prepare("INSERT INTO Admin 
                              (Admin_ID, Name, Email, Username, Password, Address, Phone_Number, Background_Check, Salary_ID) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $admin_id, $name, $email, $username, $password, $address, $phone, $background_check, $salary_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Administrator added successfully";
            header("Location: manage_admin.php");
            exit();
        } else {
            $error = "Error adding administrator: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h1>Add New Administrator</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="admin_id" class="form-label">Admin ID</label>
                    <input type="number" class="form-control" id="admin_id" name="admin_id" required>
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
                                £<?= number_format($salary['Annual_Amount'], 2) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Add Administrator</button>
            <a href="manage_admin.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
