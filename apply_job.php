<?php
session_start();
include("../database/connection.php");

/* =========================
   CHECK STUDENT LOGIN
========================= */

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = intval($_SESSION['id']);


/* =========================
   CHECK JOB ID
========================= */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_jobs.php");
    exit();
}

$job_id = intval($_GET['id']);


/* =========================
   GET JOB
========================= */

$sql = "SELECT * FROM jobs WHERE id = $job_id";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Job not found.";
    exit();
}

$job = mysqli_fetch_assoc($result);


/* =========================
   SUBMIT APPLICATION
========================= */

if (isset($_POST['apply'])) {

    $cover_message = mysqli_real_escape_string(
        $conn,
        $_POST['cover_message']
    );


    /* Check duplicate application */

    $check_sql = "
        SELECT id
        FROM job_applications
        WHERE student_id = $student_id
        AND job_id = $job_id
    ";

    $check_result = mysqli_query($conn, $check_sql);


    if ($check_result && mysqli_num_rows($check_result) > 0) {

        $error = "You have already applied for this job.";

    } else {

        $insert_sql = "
            INSERT INTO job_applications
            (student_id, job_id, cover_message, status)
            VALUES
            ($student_id, $job_id, '$cover_message', 'Pending')
        ";

        if (mysqli_query($conn, $insert_sql)) {

            echo "<script>
                    alert('Application submitted successfully!');
                    window.location.href='view_jobs.php';
                  </script>";

            exit();

        } else {

            $error = "Unable to submit application.";
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Apply for Job - Alumni Portal</title>

<link rel="stylesheet"
      href="../css/dashboard.css">


<style>

.application-wrapper {

    max-width: 800px;

    margin: 30px auto;
}


.job-header {

    background: #0b3d91;

    color: white;

    padding: 25px;

    border-radius: 12px 12px 0 0;
}


.job-header h2 {

    margin-bottom: 10px;

}


.job-header p {

    margin: 6px 0;

}


.application-card {

    background: white;

    padding: 30px;

    border-radius: 0 0 12px 12px;

    box-shadow:
        0 5px 20px
        rgba(0,0,0,0.10);
}


.application-card h2 {

    color: #0b3d91;

    margin-bottom: 10px;
}


.application-card label {

    display: block;

    margin-top: 20px;

    margin-bottom: 8px;

    font-weight: bold;

}


.application-card textarea {

    width: 100%;

    min-height: 160px;

    padding: 14px;

    border: 1px solid #ccc;

    border-radius: 8px;

    resize: vertical;

    font-size: 15px;

}


.apply-button {

    margin-top: 20px;

    padding: 13px 25px;

    border: none;

    border-radius: 7px;

    background: #0b3d91;

    color: white;

    font-size: 16px;

    font-weight: bold;

    cursor: pointer;
}


.apply-button:hover {

    background: #082f70;
}


.back-button {

    display: inline-block;

    margin-top: 18px;

    color: #0b3d91;

    text-decoration: none;

    font-weight: bold;
}


.error {

    background: #ffe5e5;

    color: #b00020;

    padding: 12px;

    border-radius: 7px;

    margin-bottom: 15px;
}

</style>

</head>


<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <h2>Student</h2>

    <a href="dashboard.php">
        🏠 Dashboard
    </a>

    <a href="profile.php">
        👤 My Profile
    </a>

    <a href="view_jobs.php">
        💼 View Jobs
    </a>

    <a href="view_events.php">
        📅 Events
    </a>

    <a href="../logout.php">
        🚪 Logout
    </a>

</div>


<!-- MAIN -->

<div class="main-content">

    <h1>Apply for Job</h1>


    <div class="application-wrapper">


        <div class="job-header">

            <h2>
                <?php
                echo htmlspecialchars(
                    $job['job_title']
                );
                ?>
            </h2>

            <p>
                🏢
                <?php
                echo htmlspecialchars(
                    $job['company']
                );
                ?>
            </p>

            <p>
                📍
                <?php
                echo htmlspecialchars(
                    $job['location']
                );
                ?>
            </p>

            <p>
                💰
                <?php
                echo htmlspecialchars(
                    $job['salary']
                );
                ?>
            </p>

        </div>


        <div class="application-card">


            <?php if (isset($error)) { ?>

                <div class="error">

                    <?php
                    echo htmlspecialchars($error);
                    ?>

                </div>

            <?php } ?>


            <h2>
                Submit Your Application
            </h2>


            <p>
                Write a short message to introduce
                yourself and express your interest
                in this position.
            </p>


            <form method="POST">


                <label>
                    Cover Message
                </label>


                <textarea
                    name="cover_message"
                    placeholder="Write your message here..."
                    required
                ></textarea>


                <button
                    type="submit"
                    name="apply"
                    class="apply-button"
                >
                    📩 Submit Application
                </button>


            </form>


            <a
                href="view_jobs.php"
                class="back-button"
            >
                ← Back to Jobs
            </a>


        </div>


    </div>

</div>


</body>

</html>