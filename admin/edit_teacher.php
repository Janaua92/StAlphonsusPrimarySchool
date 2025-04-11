<?php
session_start();
require_once '../config.php';

// Verify admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Edit Teacher";
include '../includes/header.php';

// Get teacher ID from URL
$teacherId = $_GET['id'] ?? null;

if (!$teacherId) {
    header("Location: manage_teachers.php");
    exit();
}

// Fetch teacher data
$stmt = $conn->prepare("SELECT * FROM Teachers WHERE Teacher_ID = ?");
$stmt->bind_param("i", $teacherId);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();

if (!$teacher) {
    header("Location: manage_teachers.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subjects = $_POST['subjects'];
    $qualifications = $_POST['qualifications'];

    // Update teacher
    $stmt = $conn->prepare("UPDATE Teachers SET Name=?, Email=?, Subjects=?, Qualifications=? WHERE Teacher_ID=?");
    $stmt->bind_param("ssssi", $name, $email, $subjects, $qualifications, $teacherId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Teacher updated successfully";
        header("Location: manage_teachers.php");
        exit();
    } else {
        $error = "Error updating teacher: " . $conn->error;
    }
}
?>

<div class="container mt-4">
    <h1>Edit Teacher</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($teacher['Name']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($teacher['Email']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="subjects" class="form-label">Subjects</label>
            <input type="text" class="form-control" id="subjects" name="subjects" value="<?php echo htmlspecialchars($teacher['Subjects']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="qualifications" class="form-label">Qualifications</label>
            <textarea class="form-control" id="qualifications" name="qualifications" rows="3" required><?php echo htmlspecialchars($teacher['Qualifications']); ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Teacher</button>
        <a href="manage_teachers.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
