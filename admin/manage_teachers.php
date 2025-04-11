<?php
/**
 * Teacher Management Interface
 * 
 * Allows administrators to view, edit, and delete teacher records.
 * Displays all teachers with their salary and class assignments in a sortable table.
 */

// Verify admin session
session_start();
require_once '../config.php';

// Redirect non-admins to login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Set page title and include header
$pageTitle = "Manage Teachers";
include '../includes/header.php';

/**
 * Fetch all teachers with their salary and class information
 * Includes: Teacher ID, Name, Email, Assigned Class, Salary
 * Joins with Salary and Classes tables
 */
$teachers = $conn->query("
    SELECT t.*, s.Annual_Amount, c.Name AS Class_Name 
    FROM Teacher t
    JOIN Salary s ON t.Salary_ID = s.Salary_ID
    LEFT JOIN Classes c ON t.Class_ID = c.Class_ID
");
?>

<div class="container mt-4">
    <!-- Page header with title and Add Teacher button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Teachers</h1>
        <a href="add_teacher.php" class="btn btn-success">Add New Teacher</a>
    </div>
    
    <!-- Success message display (shown after add/edit/delete) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Main teacher table card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Teacher listing table -->
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Class</th>
                            <th>Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($teacher = $teachers->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold"><?= $teacher['Teacher_ID'] ?></td>
                            <td><?= htmlspecialchars($teacher['Name']) ?></td>
                            <td><?= htmlspecialchars($teacher['Email']) ?></td>
                            <td><?= $teacher['Class_Name'] ?? 'Not Assigned' ?></td>
                            <td>£<?= number_format($teacher['Annual_Amount'], 2) ?></td>
                            <td>
                                <!-- Action buttons for each teacher -->
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_teacher.php?id=<?= $teacher['Teacher_ID'] ?>" 
                                       class="btn btn-outline-primary">Edit</a>
                                    <a href="delete_teacher.php?id=<?= $teacher['Teacher_ID'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this teacher?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
