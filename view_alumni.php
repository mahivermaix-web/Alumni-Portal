<?php
session_start();

include "../database/connection.php";

/* =========================
   CHECK LOGIN
========================= */

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


if ($_SESSION['role'] != 'student' && $_SESSION['role'] != 'alumni') {
    echo "Access Denied.";
    exit();
}



if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Alumni profile not found.";
    exit();
}

$alumni_id = intval($_GET['id']);


/* =========================
   GET ALUMNI DETAILS
========================= */

$sql = "SELECT id, full_name, email, graduation_year, department, phone, company, job_title
        FROM users
        WHERE id = $alumni_id
        AND role = 'alumni'";

$result = mysqli_query($conn, $sql);


/* =========================
   CHECK ALUMNI EXISTS
========================= */

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Alumni profile not found.";
    exit();
}


$alumni = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
    <?php echo htmlspecialchars($alumni['full_name']); ?> - Alumni Profile
</title>


<style>

/* =========================
   RESET
========================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}


/* =========================
   BODY
========================= */

body {
    background: #f4f6f9;
    color: #333;
}


/* =========================
   HEADER
========================= */

.header {

    background: #0b3d91;

    color: white;

    padding: 20px 40px;

    display: flex;

    justify-content: space-between;

    align-items: center;
}


.header h2 {
    font-size: 25px;
}


.back-btn {

    color: white;

    text-decoration: none;

    background: #1456c4;

    padding: 10px 18px;

    border-radius: 6px;

    transition: 0.3s;
}


.back-btn:hover {

    background: #1c65dd;

}


/* =========================
   MAIN CONTAINER
========================= */

.container {

    max-width: 800px;

    margin: 50px auto;

    padding: 0 20px;
}


/* =========================
   PROFILE CARD
========================= */

.profile-card {

    background: white;

    border-radius: 15px;

    padding: 40px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.1);

    text-align: center;
}


/* =========================
   PROFILE ICON
========================= */

.profile-icon {

    width: 110px;

    height: 110px;

    background: #0b3d91;

    color: white;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 50px;

    margin: 0 auto 20px auto;
}


/* =========================
   NAME
========================= */

.profile-card h1 {

    color: #0b3d91;

    font-size: 32px;

    margin-bottom: 10px;
}


.role {

    color: #666;

    font-size: 17px;

    margin-bottom: 30px;
}


/* =========================
   INFORMATION
========================= */

.profile-info {

    text-align: left;

    border-top: 1px solid #eee;

    padding-top: 20px;
}


.info-row {

    display: flex;

    justify-content: space-between;

    padding: 15px 0;

    border-bottom: 1px solid #eee;

    gap: 20px;
}


.info-label {

    font-weight: bold;

    color: #0b3d91;

    min-width: 180px;
}


.info-value {

    color: #555;

    text-align: right;

    word-break: break-word;
}


/* =========================
   BACK BUTTON
========================= */

.bottom-btn {

    display: inline-block;

    margin-top: 30px;

    background: #0b3d91;

    color: white;

    text-decoration: none;

    padding: 12px 25px;

    border-radius: 6px;

    transition: 0.3s;
}


.bottom-btn:hover {

    background: #1456c4;

}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 600px) {

    .header {

        padding: 15px 20px;

        flex-direction: column;

        gap: 15px;

        text-align: center;
    }


    .container {

        margin: 30px auto;

    }


    .profile-card {

        padding: 25px 20px;

    }


    .profile-card h1 {

        font-size: 25px;

    }


    .info-row {

        flex-direction: column;

        gap: 5px;

    }


    .info-value {

        text-align: left;

    }


    .info-label {

        min-width: auto;

    }

}

</style>

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<div class="header">

    <h2>🎓 Alumni Portal</h2>

    <a href="alumni_directory.php" class="back-btn">
        ← Back to Alumni Directory
    </a>

</div>


<!-- =========================
     PROFILE
========================= -->

<div class="container">

    <div class="profile-card">


        <!-- PROFILE ICON -->

        <div class="profile-icon">
            👤
        </div>


        <!-- ALUMNI NAME -->

        <h1>
            <?php
            echo htmlspecialchars($alumni['full_name']);
            ?>
        </h1>


        <p class="role">
            🎓 Alumni
        </p>


        <!-- ALUMNI INFORMATION -->

        <div class="profile-info">


            <!-- EMAIL -->

            <div class="info-row">

                <span class="info-label">
                    📧 Email
                </span>

                <span class="info-value">
                    <?php
                    echo htmlspecialchars($alumni['email']);
                    ?>
                </span>

            </div>


            <!-- DEPARTMENT -->

            <div class="info-row">

                <span class="info-label">
                    🎓 Department
                </span>

                <span class="info-value">

                    <?php

                    echo !empty($alumni['department'])

                        ? htmlspecialchars($alumni['department'])

                        : "Not Provided";

                    ?>

                </span>

            </div>


            <!-- GRADUATION YEAR -->

            <div class="info-row">

                <span class="info-label">
                    📅 Graduation Year
                </span>

                <span class="info-value">

                    <?php

                    echo !empty($alumni['graduation_year'])

                        ? htmlspecialchars($alumni['graduation_year'])

                        : "Not Provided";

                    ?>

                </span>

            </div>


            <!-- PHONE -->

            <div class="info-row">

                <span class="info-label">
                    📱 Phone
                </span>

                <span class="info-value">

                    <?php

                    echo !empty($alumni['phone'])

                        ? htmlspecialchars($alumni['phone'])

                        : "Not Provided";

                    ?>

                </span>

            </div>


            <!-- COMPANY -->

            <div class="info-row">

                <span class="info-label">
                    🏢 Company
                </span>

                <span class="info-value">

                    <?php

                    echo !empty($alumni['company'])

                        ? htmlspecialchars($alumni['company'])

                        : "Not Provided";

                    ?>

                </span>

            </div>


            <!-- JOB TITLE -->

            <div class="info-row">

                <span class="info-label">
                    💼 Job Title
                </span>

                <span class="info-value">

                    <?php

                    echo !empty($alumni['job_title'])

                        ? htmlspecialchars($alumni['job_title'])

                        : "Not Provided";

                    ?>

                </span>

            </div>


        </div>


        <!-- BACK BUTTON -->

        <a href="alumni_directory.php" class="bottom-btn">
            ← Back to Alumni Directory
        </a>


    </div>

</div>


</body>

</html>