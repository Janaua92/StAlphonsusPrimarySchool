<?php
/**
 * Admin Dashboard Interface
 * 
 * Main control panel for school administrators with access to all management features.
 * Includes role verification and provides quick access to all administrative functions.
 */

// Initialize session and verify admin privileges
session_start();
require_once '../config.php';

// Strict role verification - redirect non-admins to login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Set page title and include header template
$pageTitle = "Admin Dashboard";
include '../includes/header.php';
?>

<div class="container mt-4 mb-5">
    <!-- 
        Dashboard-specific styles for the card interface
        Ensures consistent appearance of all dashboard cards
    -->
    <style>
        .clickable-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            padding: 20px;
            text-align: center;
        }
        .clickable-card .card-title {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        .clickable-card .card-text {
            font-size: 0.9rem;
            line-height: 1.4;
        }
    </style>
    <!-- Dashboard Header -->
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
    <p class="lead">Administrator Dashboard</p>
    
    <!-- Main Dashboard Grid -->
    <div class="row mt-4">
        <!-- 
            USER MANAGEMENT SECTION 
            Cards for managing all user types in the system
        -->
        <div class="col-md-4 mb-4">
            <a href="manage_admin.php" class="card clickable-card">
                <div class="card-body">
                    <h5 class="card-title">Manage Administrators</h5>
                    <p class="card-text">View and manage admin accounts</p>
                </div>
            </a>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="manage_teachers.php" class="card clickable-card">
                <div class="card-body">
                    <h5 class="card-title">Manage Teachers</h5>
                    <p class="card-text">View, add, edit or remove teacher accounts</p>
                </div>
            </a>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="manage_teaching_assistants.php" class="card clickable-card">
                <div class="card-body">
                    <h5 class="card-title">Manage Teaching Assistants</h5>
                    <p class="card-text">Manage teaching assistant accounts</p>
                </div>
            </a>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="manage_students.php" class="card clickable-card">
                <div class="card-body">
                    <h5 class="card-title">Manage Students</h5>
                    <p class="card-text">View, add, edit or remove student records</p>
                </div>
            </a>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="manage_parents.php" class="card clickable-card">
                <div class="card-body">
                    <h5 class="card-title">Manage Parents</h5>
                    <p class="card-text">View, add, edit or remove parent accounts</p>
                </div>
            </a>
        </div>
        
        <!-- 
            SCHOOL RESOURCES SECTION 
            Cards for managing non-personnel resources
        -->
        <div class="col-md-4 mb-4">
            <a href="manage_classes.php" class="card clickable-card">
                <div class="card-body">
                    <h5 class="card-title">Manage Classes</h5>
                    <p class="card-text">View and manage class information</p>
                </div>
            </a>
        </div>
        
        <div class="col-md-4 mb-4">
            <a href="manage_books.php" class="card clickable-card">
                <div class="card-body">
                    <h5 class="card-title">Manage Library Books</h5>
                    <p class="card-text">View and manage library resources</p>
                </div>
            </a>
        </div>
        
        <!-- 
            FINANCIAL MANAGEMENT SECTION 
            Cards for financial and payroll administration
        -->
        <div class="col-md-4 mb-4">
            <a href="manage_salaries.php" class="card clickable-card">
                <div class="card-body">
                    <h5 class="card-title">Manage Salaries</h5>
                    <p class="card-text">View and manage salary information</p>
                </div>
            </a>
        </div>
    </div>
</div>

