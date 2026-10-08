<?php
session_start();

include("../database/connection.php");

/* Check admin login */
if (!isset($_SESSION['id']) && isset($_SESSION['user_id'])) {
    $_SESSION['id'] = $_SESSION['user_id'];
}

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'admin') {
    header("Location: ../login.php");
    exit();
}


/* Get Application ID */

$application_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($application_id <= 0) {
    header("Location: manage_applications.php");
    exit();
}


/* Fetch Application Details */

$sql = "SELECT
            ja.id AS application_id,
            ja.cover_message,
            ja.status,
            ja.applied_at,

            j.job_title,
            j.company,
            j.location,

            student.full_name AS student_name,
            student.email AS student_email,

            alumni.full_name AS alumni_name,
            alumni.email AS alumni_email

        FROM job_applications ja

        INNER JOIN jobs j
            ON ja.job_id = j.id

        INNER JOIN users student
            ON ja.student_id = student.id

        INNER JOIN users alumni
            ON j.alumni_id = alumni.id

        WHERE ja.id = '$application_id'

        LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: manage_applications.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

$status = strtolower($row['status']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Application Details - Alumni Portal</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #222;
        }

        .header {
            background: #0B1F3A;
            color: white;
            padding: 22px 30px;
        }

        .header h2 {
            margin-bottom: 5px;
        }

        .container {
            width: 95%;
            max-width: 1000px;
            margin: 30px auto;
        }

        .back-btn {
            display: inline-block;
            background: #0B1F3A;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: #C9A227;
            color: #0B1F3A;
        }

        .details-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border-left: 5px solid #0B1F3A;
        }

        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .details-header h2 {
            color: #0B1F3A;
        }

        .application-id {
            background: #eef4ff;
            color: #0B1F3A;
            padding: 8px 13px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .section {
            margin-top: 25px;
        }

        .section h3 {
            color: #0B1F3A;
            margin-bottom: 15px;
            border-bottom: 2px solid #eee;
            padding-bottom: 8px;
        }

        .info {
            margin-bottom: 11px;
            line-height: 1.6;
        }

        .label {
            font-weight: bold;
            color: #0B1F3A;
        }

        .status {
            display: inline-block;
            padding: 7px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .accepted {
            background: #d1e7dd;
            color: #0f5132;
        }

        .rejected {
            background: #f8d7da;
            color: #842029;
        }

        .message {
            background: #f8f9fa;
            padding: 18px;
            border-left: 4px solid #C9A227;
            border-radius: 6px;
            line-height: 1.6;
            color: #444;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .action-btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .accept-btn {
            background: #198754;
            color: white;
        }

        .reject-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn {
            background: #6c757d;
            color: white;
        }

        .action-btn:hover {
            opacity: 0.9;
        }

        @media (max-width: 600px) {

            .container {
                width: 92%;
            }

            .details-card {
                padding: 20px;
            }

            .details-header {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    </style>

</head>

<body>

<div class="header">

    <h2>Application Details</h2>

    <p>Admin Panel - Application Information</p>

</div>


<div class="container">

    <a href="manage_applications.php" class="back-btn">
        ← Back to Applications
    </a>


    <div class="details-card">


        <div class="details-header">

            <h2>
                <?php echo htmlspecialchars($row['job_title']); ?>
            </h2>

            <span class="application-id">
                Application #<?php echo $row['application_id']; ?>
            </span>

        </div>


        <!-- Job Information -->

        <div class="section">

            <h3>💼 Job Information</h3>

            <div class="info">
                <span class="label">Job Title:</span>
                <?php echo htmlspecialchars($row['job_title']); ?>
            </div>

            <div class="info">
                <span class="label">Company:</span>
                <?php echo htmlspecialchars($row['company']); ?>
            </div>

            <div class="info">
                <span class="label">Location:</span>
                <?php echo htmlspecialchars($row['location']); ?>
            </div>

        </div>


        <!-- Student Information -->

        <div class="section">

            <h3>👨‍🎓 Student Information</h3>

            <div class="info">
                <span class="label">Name:</span>
                <?php echo htmlspecialchars($row['student_name']); ?>
            </div>

            <div class="info">
                <span class="label">Email:</span>
                <?php echo htmlspecialchars($row['student_email']); ?>
            </div>

        </div>


        <!-- Alumni Information -->

        <div class="section">

            <h3>🎓 Alumni Information</h3>

            <div class="info">
                <span class="label">Name:</span>
                <?php echo htmlspecialchars($row['alumni_name']); ?>
            </div>

            <div class="info">
                <span class="label">Email:</span>
                <?php echo htmlspecialchars($row['alumni_email']); ?>
            </div>

        </div>


        <!-- Application Information -->

        <div class="section">

            <h3>📩 Application Information</h3>

            <div class="info">

                <span class="label">Applied On:</span>

                <?php echo htmlspecialchars($row['applied_at']); ?>

            </div>

            <div class="info">

                <span class="label">Current Status:</span>

                <span class="status <?php echo $status; ?>">

                    <?php echo htmlspecialchars($row['status']); ?>

                </span>

            </div>

        </div>


        <!-- Cover Message -->

        <div class="section">

            <h3>📝 Cover Message</h3>

            <div class="message">

                <?php
                echo nl2br(
                    htmlspecialchars($row['cover_message'])
                );
                ?>

            </div>

        </div>


        <!-- Actions -->

        <div class="actions">

            <?php if ($status == "pending") { ?>

               <a href="update_application_status.php?id=<?php echo $application_id; ?>&status=Accepted"
   style="
        display:inline-block;
        background:#198754;
        color:white;
        padding:10px 18px;
        border-radius:6px;
        text-decoration:none;
        font-weight:bold;
   ">
    ✅ Accept
</a>

              <a href="update_application_status.php?id=<?php echo $application_id; ?>&status=Rejected"
   onclick="return confirm('Are you sure you want to reject this application?');"
   style="
        display:inline-block;
        background:#dc3545;
        color:white;
        padding:10px 18px;
        border-radius:6px;
        text-decoration:none;
        font-weight:bold;
        margin-left:10px;
   ">
    ❌ Reject
</a>

            <?php } ?>


          <a href="delete_application.php?id=<?php echo $application_id; ?>"
   onclick="return confirm('Are you sure you want to delete this application?');"
   style="
        display:inline-block;
        background:#dc3545;
        color:white;
        padding:10px 18px;
        border-radius:6px;
        text-decoration:none;
        font-weight:bold;
        margin-left:10px;
   ">
    🗑️ Delete Application
</a>
        </div>


    </div>

</div>

</body>

</html>