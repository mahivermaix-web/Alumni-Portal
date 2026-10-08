<?php
session_start();

include("../database/connection.php");

/* Make session ID compatible */
if (!isset($_SESSION['id']) && isset($_SESSION['user_id'])) {
    $_SESSION['id'] = $_SESSION['user_id'];
}

/* Check login */
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

/* Check student role */
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'student') {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['id'];

/* Get student's applications */
$query = "
    SELECT 
        ja.id AS application_id,
        ja.cover_message,
        ja.status,
        ja.applied_at,
        j.job_title,
        j.company,
        j.location,
        j.salary
    FROM job_applications ja
    INNER JOIN jobs j ON ja.job_id = j.id
    WHERE ja.student_id = '$student_id'
    ORDER BY ja.applied_at DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Applications - Alumni Portal</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1b1b1b;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 40px auto;
        }

        .header {
            background: #0B1F3A;
            color: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        .header p {
            color: #dbe5f5;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            background: #0B1F3A;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .back-btn:hover {
            background: #C9A227;
            color: #0B1F3A;
        }

        .application-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
        }

        .application-card h2 {
            color: #0B1F3A;
            margin-bottom: 12px;
        }

        .info {
            margin: 8px 0;
            color: #555;
        }

        .info strong {
            color: #222;
        }

        .cover-message {
            background: #f7f8fa;
            padding: 15px;
            margin-top: 15px;
            border-radius: 8px;
        }

        .status {
            display: inline-block;
            margin-top: 15px;
            padding: 7px 14px;
            border-radius: 20px;
            background: #fff3cd;
            color: #856404;
            font-weight: bold;
        }

        .no-applications {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
        }

        .no-applications h2 {
            color: #0B1F3A;
            margin-bottom: 10px;
        }
    </style>

</head>

<body>

<div class="container">

    <a href="dashboard.php" class="back-btn">
        ← Back to Dashboard
    </a>

    <div class="header">
        <h1>📩 My Applications</h1>
        <p>View the jobs you have applied for.</p>
    </div>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>

            <div class="application-card">

                <h2>
                    <?php echo htmlspecialchars($row['job_title']); ?>
                </h2>

                <div class="info">
                    <strong>Company:</strong>
                    <?php echo htmlspecialchars($row['company']); ?>
                </div>

                <div class="info">
                    <strong>Location:</strong>
                    <?php echo htmlspecialchars($row['location']); ?>
                </div>

                <div class="info">
                    <strong>Salary:</strong>
                    <?php echo htmlspecialchars($row['salary']); ?>
                </div>

                <div class="cover-message">
                    <strong>Cover Message:</strong><br><br>
                    <?php echo nl2br(htmlspecialchars($row['cover_message'])); ?>
                </div>

                <div class="status">
                    Status:
                    <?php echo htmlspecialchars($row['status']); ?>
                </div>

                <div class="info" style="margin-top:15px;">
                    <strong>Applied On:</strong>
                    <?php echo htmlspecialchars($row['applied_at']); ?>
                </div>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="no-applications">

            <h2>No Applications Found</h2>

            <p>
                You have not applied for any job yet.
            </p>

            <br>

            <a href="view_jobs.php" class="back-btn">
                View Jobs
            </a>

        </div>

    <?php endif; ?>

</div>

</body>
</html>