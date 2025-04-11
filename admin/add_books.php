<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Add New Book";
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $book_id = (int)$_POST['book_id'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $genre = $_POST['genre'];
        $year = (int)$_POST['year'];
        $availability = 'Available';

        $stmt = $conn->prepare("INSERT INTO Libary_Books 
                              (Book_ID, Title, Author, Ganre, Published_Year, Availability) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssis", $book_id, $title, $author, $genre, $year, $availability);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Book added successfully";
            header("Location: manage_books.php");
            exit();
        } else {
            $error = "Error adding book: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h1>Add New Book</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="book_id" class="form-label">Book ID</label>
                    <input type="number" class="form-control" id="book_id" name="book_id" required>
                </div>
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control" id="author" name="author" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="genre" class="form-label">Genre</label>
                    <input type="text" class="form-control" id="genre" name="genre" required>
                </div>
                
                <div class="mb-3">
                    <label for="year" class="form-label">Published Year</label>
                    <input type="number" class="form-control" id="year" name="year" 
                           min="1900" max="<?= date('Y') ?>" required>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Add Book</button>
            <a href="manage_books.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
