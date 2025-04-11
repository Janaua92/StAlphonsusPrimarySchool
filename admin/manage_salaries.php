<?php
/**
 * Salary Management Interface
 * 
 * Allows administrators to view, edit, and delete salary records.
 * Displays all salary scales organized by role and amount.
 * Includes error handling for database operations.
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
$pageTitle = "Manage Salaries";
include '../includes/header.php';

/**
 * Fetch all salary records from database
 * Includes: Salary ID, Role, Annual Amount
 * Ordered by Role then descending Annual Amount
 * Includes error handling for failed queries
 */
$salaries = $conn->query("SELECT * FROM Salary ORDER BY Role, Annual_Amount DESC");

if (!$salaries) {
    die("Database error: " . $conn->error);
}
?>

<div class="container mt-4">
    <!-- Page header with title and Add Salary button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Salaries</h1>
        <a href="add_salary.php" class="btn btn-primary">
            Add New Salary
        </a>
    </div>
    
    <!-- Success message display (shown after add/edit/delete) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Main salary table card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Salary listing table -->
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Role</th>
                            <th>Annual Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($salaries->num_rows === 0): ?>
                            <!-- Empty state handling -->
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    No salary records found
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php while($salary = $salaries->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold"><?= $salary['Salary_ID'] ?></td>
                                <td><?= htmlspecialchars($salary['Role']) ?></td>
                                <td>£<?= number_format($salary['Annual_Amount'], 2) ?></td>
                                <td>
                                    <!-- Action buttons for each salary record -->
                                    <div class="btn-group btn-group-sm">
                                        <a href="edit_salary.php?id=<?= $salary['Salary_ID'] ?>" 
                                           class="btn btn-outline-primary">Edit</a>
                                        <a href="delete_salary.php?id=<?= $salary['Salary_ID'] ?>" 
                                           class="btn btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this salary record?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

