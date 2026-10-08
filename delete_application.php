<?php
session_start();

include("../database/connection.php");

// Check admin login
if (!isset($_SESSION['id']) && isset($_SESSION['user_id'])) {
    $_SESSION['id'] = $_SESSION['user_id'];
}

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Check admin role
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get application ID
$application_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($application_id <= 0) {
    header("Location: manage_applications.php");
    exit();
}

// Delete application
$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM job_applications WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $application_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

// Return to applications page
header("Location: manage_applications.php");
exit();
?>