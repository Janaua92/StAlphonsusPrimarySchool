<?php
session_start();
require_once '../config.php';

// Verify admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Edit Student";
include '../includes/header.php';

// Get student ID from URL
$studentId = $_GET['id'] ?? null;

if (!$studentId) {
    header("Location: manage_students.php");
    exit();
}

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM Pupils WHERE Pupils_ID = ?");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    header("Location: manage_students.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $parentId = $_POST['parent_id'];

    // Update student
    $stmt = $conn->prepare("UPDATE Pupils SET Name=?, Class=?, Date_of_Birth=?, Address=?, Parent_ID=? WHERE Pupils_ID=?");
    $stmt->bind_param("ssssii", $name, $class, $dob, $address, $parentId, $studentId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Student updated successfully";
        header("Location: manage_students.php");
        exit();
    } else {
        $error = "Error updating student: " . $conn->error;
    }
}
?>

<div class="container mt-4">
    <h1>Edit Student</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($student['Name']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="class" class="form-label">Class</label>
            <input type="text" class="form-control" id="class" name="class" value="<?php echo htmlspecialchars($student['Class']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $student['Date_of_Birth']; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($student['Address']); ?></textarea>
        </div>
        
        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent/Guardian ID</label>
            <input type="number" class="form-control" id="parent_id" name="parent_id" value="<?php echo $student['Parent_ID']; ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="manage_students.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
