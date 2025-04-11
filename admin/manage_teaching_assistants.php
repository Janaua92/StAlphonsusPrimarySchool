<?php
/**
 * Teaching Assistant Management Interface
 * 
 * Allows administrators to view, edit, and delete teaching assistant records.
 * Displays all teaching assistants with their salary and assigned classes.
 * Includes functionality to assign assistants to classes.
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
$pageTitle = "Manage Teaching Assistants";
include '../includes/header.php';

/**
 * Fetch all teaching assistants with their salary and assigned classes
 * Includes: Assistant ID, Name, Email, Salary, Assigned Classes
 * Uses GROUP_CONCAT to combine multiple class assignments into one field
 */
$assistants = $conn->query("
    SELECT ta.*, s.Annual_Amount, 
           GROUP_CONCAT(c.Name SEPARATOR ', ') AS Classes
    FROM Teaching_Assistant ta
    JOIN Salary s ON ta.Salary_ID = s.Salary_ID
    LEFT JOIN Classes_TAssistant ct ON ta.TAssistant_ID = ct.TAssistant_ID
    LEFT JOIN Classes c ON ct.Class_ID = c.Class_ID
    GROUP BY ta.TAssistant_ID
");
?>

<div class="container mt-4">
    <!-- Page header with title and Add Assistant button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Teaching Assistants</h1>
        <a href="add_teaching_assistant.php" class="btn btn-success">Add New Assistant</a>
    </div>
    
    <!-- Success message display (shown after add/edit/delete) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Main teaching assistant table card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Teaching assistant listing table -->
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Salary</th>
                            <th>Assigned Classes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($assistant = $assistants->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold"><?= $assistant['TAssistant_ID'] ?></td>
                            <td><?= htmlspecialchars($assistant['Name']) ?></td>
                            <td><?= htmlspecialchars($assistant['Email']) ?></td>
                            <td>£<?= number_format($assistant['Annual_Amount'], 2) ?></td>
                            <td><?= $assistant['Classes'] ?? 'Not Assigned' ?></td>
                            <td>
                                <!-- Action buttons for each assistant -->
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_teaching_assistant.php?id=<?= $assistant['TAssistant_ID'] ?>" 
                                       class="btn btn-outline-primary">Edit</a>
                                    <a href="delete_teaching_assistant.php?id=<?= $assistant['TAssistant_ID'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this teaching assistant?')">Delete</a>
                                    <a href="assign_classes.php?id=<?= $assistant['TAssistant_ID'] ?>" 
                                       class="btn btn-outline-secondary">Assign</a>
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

