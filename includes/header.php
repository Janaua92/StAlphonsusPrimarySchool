<?php
/**
 * Shared Header Template
 * 
 * Contains the common header, navigation, and styles used across all pages.
 * Automatically handles session management and role-based navigation.
 */

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Dynamic page title with fallback to default -->
    <title><?php echo $pageTitle ?? 'St. Alphonsus Primary School'; ?></title>
    
    <!-- External CSS libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom styles for the application -->
    <style>
        /* Navigation styling */
        .navbar-brand { font-weight: 600; }
        .nav-link.active { font-weight: 500; } /* Active nav item indicator */
        
        /* Clickable card styles */
        .clickable-card {
            display: flex;
            flex-direction: column;
            height: 200px; /* Fixed height */
            width: 100%; /* Full width of column */
            color: inherit;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 0.25rem;
            overflow: hidden; /* Prevent content overflow */
        }
        .clickable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #f8f9fa;
        }
        .clickable-card .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            flex-grow: 1;
        }
        .clickable-card .card-title {
            color: #0d6efd;
            margin-bottom: 0.75rem;
        }
        .clickable-card .card-text {
            margin-bottom: 0;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .col-md-4 {
            display: flex;
            flex: 0 0 calc(33.333% - 30px);
            max-width: calc(33.333% - 30px);
            margin: 15px;
        }
        @media (max-width: 992px) {
            .col-md-4 {
                flex: 0 0 calc(50% - 30px);
                max-width: calc(50% - 30px);
            }
        }
        @media (max-width: 768px) {
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Main navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <!-- Brand/logo -->
            <a class="navbar-brand" href="../dashboard.php">St. Alphonsus</a>
            
            <!-- Mobile menu toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isset($_SESSION['role'])): ?>
                        <!-- Dashboard link (active when on dashboard) -->
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>" 
                               href="../dashboard.php">Dashboard</a>
                        </li>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    Admin
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="../admin/manage_teachers.php">Teachers</a></li>
                                    <li><a class="dropdown-item" href="../admin/manage_students.php">Students</a></li>
                                    <li><a class="dropdown-item" href="../admin/manage_parents.php">Parents</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <!-- Right-aligned navigation items -->
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['username'])): ?>
                        <!-- User greeting and logout for authenticated users -->
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <!-- Login link for guests -->
                        <li class="nav-item">
                            <a class="nav-link" href="../login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
