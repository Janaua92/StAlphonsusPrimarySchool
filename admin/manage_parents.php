<?php
/**
 * Parent/Guardian Management Interface
 * 
 * Allows administrators to view, edit, and delete parent/guardian records.
 * Displays all parents with their associated children in a sortable table.
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
$pageTitle = "Manage Parents/Guardians";
include '../includes/header.php';

/**
 * Fetch all parents with their associated children
 * Includes: Parent ID, Name, Email, Phone, Children list
 * Uses GROUP_CONCAT to combine multiple children into one field
 */
$parents = $conn->query("
    SELECT p.*, GROUP_CONCAT(s.Name SEPARATOR ', ') AS Children
    FROM `Parents/Guardians` p
    LEFT JOIN Pupils_Parent pp ON p.Parent_ID = pp.Parent_ID
    LEFT JOIN Pupils s ON pp.Pupils_ID = s.Pupils_ID
    GROUP BY p.Parent_ID
") or die("Database error: " . $conn->error);
?>

<div class="container mt-4">
    <!-- Page header with title and Add Parent button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Parents/Guardians</h1>
        <a href="add_parent.php" class="btn btn-success">Add New Parent/Guardian</a>
    </div>
    
    <!-- Success message display (shown after add/edit/delete) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Main parent table card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Parent listing table -->
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Children</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($parents && $parents->num_rows > 0) {
                            while($parent = $parents->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td class="fw-bold">' . $parent['Parent_ID'] . '</td>';
                                echo '<td>' . htmlspecialchars($parent['Name']) . '</td>';
                                echo '<td>' . htmlspecialchars($parent['Email']) . '</td>';
                                echo '<td>' . htmlspecialchars($parent['Phone_Number']) . '</td>';
                                echo '<td>' . ($parent['Children'] ?? 'None') . '</td>';
                                echo '<td>';
                                echo '<div class="btn-group btn-group-sm">';
                                echo '<a href="edit_parent.php?id=' . $parent['Parent_ID'] . '" class="btn btn-outline-primary">Edit</a>';
                                echo '<a href="delete_parent.php?id=' . $parent['Parent_ID'] . '" class="btn btn-outline-danger" onclick="return confirm(\'Are you sure you want to delete this parent/guardian?\')">Delete</a>';
                                echo '</div>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="6" class="text-center">No parents found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
