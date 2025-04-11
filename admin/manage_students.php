<?php
/**
 * Student Management Interface
 * 
 * Allows administrators to view, edit, and delete student records.
 * Displays all students with their class assignments and basic information.
 * Includes functionality to add, edit, view and delete student records.
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
$pageTitle = "Manage Students";
include '../includes/header.php';

/**
 * Fetch all students with their class information
 * Includes: Student ID, Name, Age, Class, Email
 * Ordered by Class then Name for organized display
 * Uses LEFT JOIN to include students without class assignments
 */
$students = $conn->query("
    SELECT p.*, c.Name AS Class_Name 
    FROM Pupils p
    LEFT JOIN Classes c ON p.Class_ID = c.Class_ID
    ORDER BY p.Class_ID, p.Name
");
?>

<div class="container mt-4">
    <!-- Page header with title and Add Student button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Students</h1>
        <a href="add_student.php" class="btn btn-success">Add New Student</a>
    </div>
    
    <!-- Success message display (shown after add/edit/delete) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Main student table card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Student listing table -->
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Class</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($student = $students->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold"><?= $student['Pupils_ID'] ?></td>
                            <td><?= htmlspecialchars($student['Name']) ?></td>
                            <td><?= $student['Age'] ?></td>
                            <td><?= $student['Class_Name'] ?? 'Not Assigned' ?></td>
                            <td><?= htmlspecialchars($student['Email']) ?></td>
                            <td>
                                <!-- Action buttons for each student -->
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_student.php?id=<?= $student['Pupils_ID'] ?>" 
                                       class="btn btn-outline-primary">Edit</a>
                                    <a href="delete_student.php?id=<?= $student['Pupils_ID'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                    <a href="view_student.php?id=<?= $student['Pupils_ID'] ?>" 
                                       class="btn btn-outline-secondary">View</a>
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
