<?php
/**
 * Administrator Management Interface
 * 
 * Allows super administrators to view, edit, and delete administrator accounts.
 * Displays all administrators with their salary and background check status.
 * Prevents self-deletion of the currently logged-in admin account.
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
$pageTitle = "Manage Administrators";
include '../includes/header.php';

/**
 * Fetch all administrators with their salary information
 * Includes: Admin ID, Name, Email, Username, Salary, Background Check status
 * Ordered by Admin_ID for consistent display
 */
$admins = $conn->query("
    SELECT a.*, s.Annual_Amount 
    FROM Admin a
    JOIN Salary s ON a.Salary_ID = s.Salary_ID
    ORDER BY a.Admin_ID
");
?>

<div class="container mt-4">
    <!-- Page header with title and Add Admin button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Administrators</h1>
        <a href="add_admin.php" class="btn btn-success">Add New Administrator</a>
    </div>
    
    <!-- Success message display (shown after add/edit/delete) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Main admin table card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Admin listing table -->
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Salary</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($admin = $admins->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold"><?= $admin['Admin_ID'] ?></td>
                            <td><?= htmlspecialchars($admin['Name']) ?></td>
                            <td><?= htmlspecialchars($admin['Email']) ?></td>
                            <td><?= htmlspecialchars($admin['Username']) ?></td>
                            <td>£<?= number_format($admin['Annual_Amount'], 2) ?></td>
                            <td>
                                <!-- Background check status with color coding -->
                                <span class="badge bg-<?= 
                                    $admin['Background_Check'] === 'Clear' ? 'success' : 
                                    ($admin['Background_Check'] === 'Pending' ? 'warning' : 'danger') 
                                ?>">
                                    <?= $admin['Background_Check'] ?>
                                </span>
                            </td>
                            <td>
                                <!-- Action buttons for each admin -->
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_admin.php?id=<?= $admin['Admin_ID'] ?>" 
                                       class="btn btn-outline-primary">Edit</a>
                                    <!-- Prevent self-deletion -->
                                    <?php if ($_SESSION['user_id'] != $admin['Admin_ID']): ?>
                                    <a href="delete_admin.php?id=<?= $admin['Admin_ID'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this administrator?')">Delete</a>
                                    <?php endif; ?>
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

