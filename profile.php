<?php
session_start();

include("../database/connection.php");

/* =========================================
   CHECK LOGIN
   Supports both session names
========================================= */

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
}
elseif (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
}
else {
    header("Location: ../login.php");
    exit();
}


/* =========================================
   GET STUDENT DATA
========================================= */

$id = intval($id);

$sql = "SELECT * FROM users WHERE id = '$id' LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    die("User profile not found.");
}

$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile - Student Portal</title>

<style>

/* =========================================
   RESET
========================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}


/* =========================================
   BODY
========================================= */

body {
    background: #f4f6f9;
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

    background: #0b3d91;

    padding-top: 30px;

    overflow-y: auto;
}


.sidebar h2 {
    color: white;

    text-align: center;

    margin-bottom: 30px;

    font-size: 24px;
}


.sidebar a {
    display: block;

    color: white;

    text-decoration: none;

    padding: 14px 25px;

    font-size: 15px;

    transition: 0.3s;
}


.sidebar a:hover {
    background: #1456c4;
}


/* ACTIVE PAGE */

.sidebar a.active {
    background: #1456c4;

    border-left: 4px solid #ffc107;
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

    font-size: 34px;

    margin-bottom: 8px;
}


.subtitle {
    color: #666;

    margin-bottom: 30px;

    font-size: 16px;
}


/* =========================================
   PROFILE CARD
========================================= */

.profile-card {

    max-width: 850px;

    background: white;

    border-radius: 18px;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);

    overflow: hidden;

    margin-top: 20px;
}


/* =========================================
   PROFILE HEADER
========================================= */

.profile-header {

    background: linear-gradient(
        135deg,
        #0b3d91,
        #1456c4
    );

    color: white;

    padding: 35px;

    display: flex;

    align-items: center;

    gap: 25px;
}


/* PROFILE ICON */

.profile-icon {

    width: 85px;

    height: 85px;

    border-radius: 50%;

    background: white;

    color: #0b3d91;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 40px;

    box-shadow:
        0 5px 15px rgba(0,0,0,0.2);
}


.profile-header h2 {

    font-size: 26px;

    margin-bottom: 8px;
}


.profile-header p {

    font-size: 15px;

    opacity: 0.9;
}


/* =========================================
   PROFILE DETAILS
========================================= */

.profile-details {

    padding: 35px;

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 25px;
}


/* =========================================
   DETAIL BOX
========================================= */

.detail-box {

    background: #f8f9fc;

    padding: 20px;

    border-radius: 10px;

    border: 1px solid #e6e8ed;

    transition: 0.3s;
}


.detail-box:hover {

    transform: translateY(-3px);

    box-shadow:
        0 5px 15px rgba(0,0,0,0.08);
}


.detail-label {

    color: #777;

    font-size: 13px;

    margin-bottom: 8px;

    font-weight: bold;

    text-transform: uppercase;
}


.detail-value {

    color: #222;

    font-size: 16px;

    font-weight: 600;

    word-break: break-word;
}


/* =========================================
   ROLE BADGE
========================================= */

.role-badge {

    display: inline-block;

    background: #e8f0ff;

    color: #0b3d91;

    padding: 6px 14px;

    border-radius: 20px;

    font-size: 13px;

    font-weight: bold;

    text-transform: capitalize;
}


/* =========================================
   BACK BUTTON
========================================= */

.back-btn {

    display: inline-block;

    margin-top: 25px;

    padding: 12px 22px;

    background: #0b3d91;

    color: white;

    text-decoration: none;

    border-radius: 7px;

    font-weight: bold;

    transition: 0.3s;
}


.back-btn:hover {

    background: #1456c4;

    transform: translateY(-2px);
}


/* =========================================
   RESPONSIVE
========================================= */

@media(max-width: 800px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;

        padding: 15px 0;
    }


    .sidebar h2 {

        margin-bottom: 10px;
    }


    .sidebar a {

        display: inline-block;

        padding: 10px 12px;

        font-size: 13px;
    }


    .main-content {

        margin-left: 0;

        padding: 25px 15px;
    }


    .profile-details {

        grid-template-columns: 1fr;

        padding: 20px;
    }


    .profile-header {

        padding: 25px;

        flex-direction: column;

        text-align: center;
    }

}

</style>

</head>


<body>


<!-- =========================================
     SIDEBAR
========================================= -->

<div class="sidebar">

    <h2>🎓 Student Portal</h2>


    <a href="dashboard.php">
        🏠 Dashboard
    </a>


    <a href="view_events.php">
        📅 Events
    </a>


    <a href="search_alumni.php">
        🔍 Search Alumni
    </a>


    <a href="announcements.php">
        📢 Announcements
    </a>


    <a href="alumni_directory.php">
        🎓 Alumni Directory
    </a>

    <a href="profile.php" class="active">
        👤 My Profile
    </a>


    <a href="view_jobs.php">
        💼 Jobs
    </a>


    <a href="../logout.php">
        🚪 Logout
    </a>

</div>


<!-- =========================================
     MAIN CONTENT
========================================= -->

<div class="main-content">


    <h1>My Profile</h1>

    <p class="subtitle">
        View your personal and academic information.
    </p>


    <!-- PROFILE CARD -->

    <div class="profile-card">


        <!-- PROFILE HEADER -->

        <div class="profile-header">


            <div class="profile-icon">
                👤
            </div>


            <div>

                <h2>
                    <?php
                    echo htmlspecialchars(
                        $row['full_name'] ?? 'Student'
                    );
                    ?>
                </h2>


                <p>
                    Student Profile
                </p>

            </div>

        </div>


        <!-- PROFILE DETAILS -->

        <div class="profile-details">


            <!-- NAME -->

            <div class="detail-box">

                <div class="detail-label">
                    Full Name
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $row['full_name'] ?? 'Not Available'
                    );
                    ?>

                </div>

            </div>


            <!-- EMAIL -->

            <div class="detail-box">

                <div class="detail-label">
                    Email Address
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $row['email'] ?? 'Not Available'
                    );
                    ?>

                </div>

            </div>


            <!-- DEPARTMENT -->

            <div class="detail-box">

                <div class="detail-label">
                    Department
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $row['department'] ?? 'Not Available'
                    );
                    ?>

                </div>

            </div>


            <!-- GRADUATION YEAR -->

            <div class="detail-box">

                <div class="detail-label">
                    Graduation Year
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $row['graduation_year'] ?? 'Not Available'
                    );
                    ?>

                </div>

            </div>


            <!-- PHONE -->

            <div class="detail-box">

                <div class="detail-label">
                    Phone Number
                </div>

                <div class="detail-value">

                    <?php
                    echo htmlspecialchars(
                        $row['phone'] ?? 'Not Available'
                    );
                    ?>

                </div>

            </div>


            <!-- ROLE -->

            <div class="detail-box">

                <div class="detail-label">
                    Account Type
                </div>

                <div class="detail-value">

                    <span class="role-badge">

                        <?php
                        echo htmlspecialchars(
                            $row['role'] ?? 'Student'
                        );
                        ?>

                    </span>

                </div>

            </div>

        </div>

    </div>


    <!-- BACK BUTTON -->

    <a href="dashboard.php" class="back-btn">
        ← Back to Dashboard
    </a>


</div>


</body>

</html>