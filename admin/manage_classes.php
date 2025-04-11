<?php
/**
 * Class Management Interface
 * 
 * Allows administrators to view, edit, and delete class records.
 * Displays all classes with their assigned teachers and teaching assistants in a sortable table.
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
$pageTitle = "Manage Classes";
include '../includes/header.php';

/**
 * Fetch all classes with their teacher and assistant information
 * Includes: Class ID, Name, Capacity, Assigned Teacher, Number of Assistants
 * Joins with Teacher and Classes_TAssistant tables
 */
$classes = $conn->query("
    SELECT c.*, t.Name AS Teacher_Name, 
           COUNT(ct.TAssistant_ID) AS Assistant_Count
    FROM Classes c
    LEFT JOIN Teacher t ON c.Teacher_ID = t.Teacher_ID
    LEFT JOIN Classes_TAssistant ct ON c.Class_ID = ct.Class_ID
    GROUP BY c.Class_ID
");
?>

<div class="container mt-4">
    <!-- Page header with title and Add Class button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Classes</h1>
        <a href="add_class.php" class="btn btn-success">Add New Class</a>
    </div>
    
    <!-- Success message display (shown after add/edit/delete) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Main class table card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Class listing table -->
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Class Name</th>
                            <th>Capacity</th>
                            <th>Teacher</th>
                            <th>Assistants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($class = $classes->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold"><?= $class['Class_ID'] ?></td>
                            <td><?= htmlspecialchars($class['Name']) ?></td>
                            <td><?= $class['Capacity'] ?></td>
                            <td><?= $class['Teacher_Name'] ?? 'Not Assigned' ?></td>
                            <td><?= $class['Assistant_Count'] ?></td>
                            <td>
                                <!-- Action buttons for each class -->
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_class.php?id=<?= $class['Class_ID'] ?>" 
                                       class="btn btn-outline-primary">Edit</a>
                                    <a href="delete_class.php?id=<?= $class['Class_ID'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this class?')">Delete</a>
                                    <a href="assign_students.php?id=<?= $class['Class_ID'] ?>" 
                                       class="btn btn-outline-secondary">Students</a>
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

