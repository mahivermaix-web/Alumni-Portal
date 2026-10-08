<?php
session_start();

include("../database/connection.php");

/* =========================================
   CHECK LOGIN
   Supports both session names
========================================= */

if (isset($_SESSION['user_id'])) {
    $student_id = $_SESSION['user_id'];
}
elseif (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
}
else {
    header("Location: ../login.php");
    exit();
}


/* =========================================
   GET EVENTS
========================================= */

$sql = "SELECT * FROM events ORDER BY event_date ASC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Events - Student Portal</title>


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


/* ACTIVE LINK */

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

    font-size: 16px;

    margin-bottom: 30px;
}


/* =========================================
   EVENTS GRID
========================================= */

.events-grid {

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(300px, 1fr));

    gap: 25px;

    max-width: 1100px;
}


/* =========================================
   EVENT CARD
========================================= */

.event-card {

    background: white;

    border-radius: 15px;

    overflow: hidden;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);

    transition:
        transform 0.3s,
        box-shadow 0.3s;

    border: 1px solid #e8e8e8;
}


.event-card:hover {

    transform: translateY(-6px);

    box-shadow:
        0 12px 30px rgba(0,0,0,0.14);
}


/* =========================================
   EVENT CARD HEADER
========================================= */

.event-header {

    background: linear-gradient(
        135deg,
        #0b3d91,
        #1456c4
    );

    color: white;

    padding: 25px;
}


.event-header h2 {

    font-size: 21px;

    line-height: 1.4;
}


/* =========================================
   EVENT CONTENT
========================================= */

.event-body {

    padding: 25px;
}


.event-info {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-bottom: 15px;

    color: #444;

    font-size: 15px;

    line-height: 1.5;
}


.event-info strong {

    color: #0b3d91;
}


.event-description {

    border-top: 1px solid #eee;

    padding-top: 18px;

    margin-top: 18px;

    color: #666;

    line-height: 1.6;

    font-size: 14px;
}


/* =========================================
   DATE BADGE
========================================= */

.date-badge {

    display: inline-block;

    background: #ffc107;

    color: #0b3d91;

    padding: 6px 12px;

    border-radius: 20px;

    font-size: 12px;

    font-weight: bold;

    margin-bottom: 12px;
}


/* =========================================
   NO EVENTS
========================================= */

.no-events {

    background: white;

    max-width: 700px;

    padding: 50px;

    text-align: center;

    border-radius: 15px;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);
}


.no-events-icon {

    font-size: 50px;

    margin-bottom: 15px;
}


.no-events h2 {

    color: #0b3d91;

    margin-bottom: 10px;
}


.no-events p {

    color: #777;

    line-height: 1.5;
}


/* =========================================
   BACK BUTTON
========================================= */

.back-btn {

    display: inline-block;

    margin-top: 30px;

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


    .main-content h1 {

        font-size: 28px;
    }


    .events-grid {

        grid-template-columns: 1fr;
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


    <a href="view_events.php" class="active">
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


    <a href="profile.php">
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


    <h1>Upcoming Events</h1>

    <p class="subtitle">
        Stay updated with the latest events and activities organized by the Alumni Portal.
    </p>


    <?php

    if ($result && mysqli_num_rows($result) > 0) {

    ?>


    <!-- EVENTS GRID -->

    <div class="events-grid">


    <?php

    while ($row = mysqli_fetch_assoc($result)) {

    ?>


        <!-- EVENT CARD -->

        <div class="event-card">


            <div class="event-header">

                <div class="date-badge">
                    📅
                    <?php
                    echo htmlspecialchars(
                        $row['event_date']
                    );
                    ?>
                </div>


                <h2>
                    <?php
                    echo htmlspecialchars(
                        $row['event_name']
                    );
                    ?>
                </h2>

            </div>


            <div class="event-body">


                <!-- VENUE -->

                <div class="event-info">

                    <span>📍</span>

                    <div>

                        <strong>Venue</strong><br>

                        <?php
                        echo htmlspecialchars(
                            $row['venue']
                        );
                        ?>

                    </div>

                </div>


                <!-- DATE -->

                <div class="event-info">

                    <span>🗓️</span>

                    <div>

                        <strong>Event Date</strong><br>

                        <?php
                        echo htmlspecialchars(
                            $row['event_date']
                        );
                        ?>

                    </div>

                </div>


                <!-- DESCRIPTION -->

                <div class="event-description">

                    <strong>
                        📝 About This Event
                    </strong>

                    <br><br>

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $row['description']
                        )
                    );

                    ?>

                </div>


            </div>

        </div>


    <?php

    }

    ?>


    </div>


    <?php

    }

    else {

    ?>


        <!-- NO EVENTS MESSAGE -->

        <div class="no-events">

            <div class="no-events-icon">
                📅
            </div>


            <h2>
                No Upcoming Events
            </h2>


            <p>
                There are currently no events available.
                Please check back later for new events and activities.
            </p>

        </div>


    <?php

    }

    ?>


    <!-- BACK BUTTON -->

    <a href="dashboard.php" class="back-btn">
        ← Back to Dashboard
    </a>


</div>


</body>

</html>