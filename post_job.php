<?php
session_start();

include("../database/connection.php");

/* =========================================
   CHECK ALUMNI LOGIN
========================================= */

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
    header("Location: ../login.php");
    exit();
}


/* =========================================
   POST JOB
========================================= */

if (isset($_POST['post_job'])) {

    $alumni_id = $_SESSION['user_id'];

    $company = trim($_POST['company'] ?? '');
    $job_title = trim($_POST['job_title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $salary = trim($_POST['salary'] ?? '');


    /* Check empty fields */

    if (
        empty($company) ||
        empty($job_title) ||
        empty($description) ||
        empty($location) ||
        empty($salary)
    ) {

        $error = "Please fill in all fields.";

    } else {

        /* Secure database insertion */

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO jobs
            (alumni_id, company, job_title, description, location, salary)
            VALUES (?, ?, ?, ?, ?, ?)"
        );


        mysqli_stmt_bind_param(
            $stmt,
            "isssss",
            $alumni_id,
            $company,
            $job_title,
            $description,
            $location,
            $salary
        );


        if (mysqli_stmt_execute($stmt)) {

            echo "<script>
                    alert('Job Posted Successfully!');
                    window.location='post_job.php';
                  </script>";

            exit();

        } else {

            $error = "Error posting job: " . mysqli_error($conn);

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

<title>Post Job | Alumni Portal</title>


<style>

/* =========================================
   RESET
========================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
}


/* =========================================
   BODY
========================================= */

body {
    background: #f4f7fb;
    color: #1e293b;
}


/* =========================================
   SIDEBAR
========================================= */

.sidebar {

    position: fixed;

    left: 0;

    top: 0;

    width: 250px;

    height: 100vh;

    background: linear-gradient(
        180deg,
        #0b3d91,
        #082b68
    );

    padding: 25px 15px;

    box-shadow: 4px 0 15px rgba(0,0,0,0.12);

}


.sidebar h2 {

    color: white;

    text-align: center;

    margin-bottom: 30px;

    padding-bottom: 20px;

    border-bottom: 1px solid rgba(255,255,255,0.2);

}


.sidebar a {

    display: block;

    color: white;

    text-decoration: none;

    padding: 14px 18px;

    margin-bottom: 6px;

    border-radius: 8px;

    transition: 0.3s;

}


.sidebar a:hover {

    background: rgba(255,255,255,0.15);

    transform: translateX(4px);

}


.sidebar a.active {

    background: white;

    color: #0b3d91;

    font-weight: bold;

}


/* =========================================
   MAIN CONTENT
========================================= */

.main-content {

    margin-left: 250px;

    padding: 40px;

    min-height: 100vh;

}


.main-content h1 {

    color: #0b3d91;

    margin-bottom: 8px;

}


.subtitle {

    color: #64748b;

}


/* =========================================
   JOB FORM
========================================= */

.job-box {

    background: white;

    width: 100%;

    max-width: 700px;

    padding: 30px;

    margin-top: 30px;

    border-radius: 12px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.08);

}


.job-box label {

    display: block;

    margin-top: 18px;

    margin-bottom: 8px;

    font-weight: bold;

    color: #334155;

}


.job-box input,

.job-box textarea {

    width: 100%;

    padding: 13px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    font-size: 15px;

    outline: none;

}


.job-box input:focus,

.job-box textarea:focus {

    border-color: #0b3d91;

    box-shadow: 0 0 0 3px rgba(11,61,145,0.1);

}


.job-box textarea {

    height: 140px;

    resize: vertical;

}


/* =========================================
   BUTTON
========================================= */

.post-btn {

    margin-top: 22px;

    padding: 13px 30px;

    background: #0b3d91;

    color: white;

    border: none;

    border-radius: 7px;

    cursor: pointer;

    font-size: 16px;

    font-weight: bold;

}


.post-btn:hover {

    background: #1456c4;

}


/* =========================================
   ERROR
========================================= */

.error-message {

    max-width: 700px;

    background: #fee2e2;

    color: #991b1b;

    padding: 12px 15px;

    border-radius: 7px;

    margin-top: 20px;

}


/* =========================================
   RESPONSIVE
========================================= */

@media (max-width: 700px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;

    }


    .main-content {

        margin-left: 0;

        padding: 20px;

    }

}

</style>

</head>


<body>


<!-- =========================================
     SIDEBAR
========================================= -->

<div class="sidebar">

    <h2>🎓 Alumni</h2>


    <a href="dashboard.php">

        🏠 Dashboard

    </a>


    <a href="profile.php">

        👤 My Profile

    </a>


    <a href="post_job.php" class="active">

        💼 Post Job

    </a>


    <a href="post_event.php">

        📅 Post Event

    </a>


    <a href="post_announcement.php">

        📢 Post Announcement

    </a>


    <a href="view_students.php">

        🎓 View Students

    </a>


    <a href="../logout.php">

        🚪 Logout

    </a>

</div>


<!-- =========================================
     MAIN CONTENT
========================================= -->

<div class="main-content">


    <h1>💼 Post a Job</h1>


    <p class="subtitle">

        Share job opportunities with students through the Alumni Portal.

    </p>


    <?php if (isset($error)): ?>

        <div class="error-message">

            <?php echo htmlspecialchars($error); ?>

        </div>

    <?php endif; ?>


    <div class="job-box">


        <form method="POST">


            <label>

                Company Name

            </label>


            <input

                type="text"

                name="company"

                placeholder="Enter company name"

                required

            >


            <label>

                Job Title

            </label>


            <input

                type="text"

                name="job_title"

                placeholder="e.g. Software Developer"

                required

            >


            <label>

                Job Description

            </label>


            <textarea

                name="description"

                placeholder="Describe the job role and requirements..."

                required

            ></textarea>


            <label>

                Location

            </label>


            <input

                type="text"

                name="location"

                placeholder="e.g. Nagpur, Maharashtra"

                required

            >


            <label>

                Salary

            </label>


            <input

                type="text"

                name="salary"

                placeholder="e.g. ₹5,00,000 per year"

                required

            >


            <button

                type="submit"

                name="post_job"

                class="post-btn"

            >

                💼 Post Job

            </button>


        </form>


    </div>


</div>


</body>

</html>