<?php
/**
 * Library Book Management Interface
 * 
 * Allows administrators to view, edit, and delete library book records.
 * Displays all books with their availability status and basic information.
 * Includes color-coded status indicators for quick visual reference.
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
$pageTitle = "Manage Library Books";
include '../includes/header.php';

/**
 * Fetch all books from the library database
 * Includes: Book ID, Title, Author, Genre, Published Year, Availability
 * Ordered by Title for easier browsing
 */
$books = $conn->query("SELECT * FROM Libary_Books ORDER BY Title");
?>

<div class="container mt-4">
    <!-- Page header with title and Add Book button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Library Books</h1>
        <a href="add_books.php" class="btn btn-success">Add New Book</a>
    </div>
    
    <!-- Success message display (shown after add/edit/delete) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Main book table card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <!-- Book listing table -->
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Year</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($book = $books->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold"><?= $book['Book_ID'] ?></td>
                            <td><?= htmlspecialchars($book['Title']) ?></td>
                            <td><?= htmlspecialchars($book['Author']) ?></td>
                            <td><?= htmlspecialchars($book['Ganre']) ?></td>
                            <td><?= $book['Published_Year'] ?></td>
                            <td>
                                <!-- Color-coded availability status -->
                                <span class="badge bg-<?= 
                                    $book['Availability'] === 'Available' ? 'success' : 
                                    ($book['Availability'] === 'Checked Out' ? 'warning' : 'danger') 
                                ?>">
                                    <?= $book['Availability'] ?>
                                </span>
                            </td>
                            <td>
                                <!-- Action buttons for each book -->
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_book.php?id=<?= $book['Book_ID'] ?>" 
                                       class="btn btn-outline-primary">Edit</a>
                                    <a href="delete_book.php?id=<?= $book['Book_ID'] ?>" 
                                       class="btn btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
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

