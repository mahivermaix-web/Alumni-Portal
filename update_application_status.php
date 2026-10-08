<?php
session_start();

include("../database/connection.php");

/* Check alumni login */
if (!isset($_SESSION['id']) && isset($_SESSION['user_id'])) {
    $_SESSION['id'] = $_SESSION['user_id'];
}

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'alumni') {
    header("Location: ../login.php");
    exit();
}

$application_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

/* Allow only these statuses */
if ($application_id <= 0 || !in_array($status, ['Accepted', 'Rejected'])) {
    header("Location: applications.php");
    exit();
}

/* Update application status */
$stmt = mysqli_prepare(
    $conn,
    "UPDATE job_applications 
     SET status = ? 
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "si", $status, $application_id);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

/* Return to applications page */
header("Location: applications.php");
exit();
?>