<?php
session_start();
require_once '../config.php';

// Verify admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Edit Class";
include '../includes/header.php';

// Get class ID from URL
$classId = $_GET['id'] ?? null;

if (!$classId) {
    header("Location: manage_classes.php");
    exit();
}

// Fetch class data
$stmt = $conn->prepare("SELECT * FROM Classes WHERE Class_ID = ?");
$stmt->bind_param("i", $classId);
$stmt->execute();
$class = $stmt->get_result()->fetch_assoc();

if (!$class) {
    header("Location: manage_classes.php");
    exit();
}

// Fetch all teachers for dropdown
$teachers = $conn->query("SELECT Teacher_ID, Name FROM Teachers");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $className = $_POST['class_name'];
    $yearGroup = $_POST['year_group'];
    $teacherId = $_POST['teacher_id'] ?: null;

    // Update class
    $stmt = $conn->prepare("UPDATE Classes SET ClassName=?, YearGroup=?, Teacher_ID=? WHERE Class_ID=?");
    $stmt->bind_param("ssii", $className, $yearGroup, $teacherId, $classId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Class updated successfully";
        header("Location: manage_classes.php");
        exit();
    } else {
        $error = "Error updating class: " . $conn->error;
    }
}
?>

<div class="container mt-4">
    <h1>Edit Class</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="class_name" class="form-label">Class Name</label>
            <input type="text" class="form-control" id="class_name" name="class_name" 
                   value="<?php echo htmlspecialchars($class['ClassName']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="year_group" class="form-label">Year Group</label>
            <input type="text" class="form-control" id="year_group" name="year_group" 
                   value="<?php echo htmlspecialchars($class['YearGroup']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="teacher_id" class="form-label">Class Teacher</label>
            <select class="form-select" id="teacher_id" name="teacher_id">
                <option value="">-- Select Teacher --</option>
                <?php while($teacher = $teachers->fetch_assoc()): ?>
                    <option value="<?php echo $teacher['Teacher_ID']; ?>"
                        <?php if($teacher['Teacher_ID'] == $class['Teacher_ID']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($teacher['Name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Class</button>
        <a href="manage_classes.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
