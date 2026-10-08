<?php
session_start();

include("../database/connection.php");

/* =========================================
   CHECK LOGIN
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
   GET ANNOUNCEMENTS
========================================= */

$sql = "
    SELECT id, title, message, created_at
    FROM announcements
    ORDER BY created_at DESC, id DESC
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Announcements - Student Portal</title>


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
   ANNOUNCEMENT CONTAINER
========================================= */

.announcement-container {

    max-width: 1000px;

}


/* =========================================
   ANNOUNCEMENT CARD
========================================= */

.announcement-card {

    position: relative;

    background: white;

    padding: 28px 30px;

    margin-bottom: 22px;

    border-radius: 15px;

    border-left: 5px solid #0b3d91;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);

    transition:
        transform 0.3s,
        box-shadow 0.3s;

}


.announcement-card:hover {

    transform: translateY(-4px);

    box-shadow:
        0 12px 30px rgba(0,0,0,0.13);

}


/* =========================================
   ANNOUNCEMENT HEADER
========================================= */

.announcement-header {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    gap: 20px;

    margin-bottom: 15px;

}


.announcement-title {

    display: flex;

    align-items: center;

    gap: 12px;

}


.announcement-icon {

    width: 45px;

    height: 45px;

    background: #e8f0ff;

    color: #0b3d91;

    border-radius: 10px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 22px;

}


.announcement-card h2 {

    color: #0b3d91;

    font-size: 21px;

    line-height: 1.3;

}


.announcement-date {

    background: #f0f3f8;

    color: #666;

    padding: 7px 12px;

    border-radius: 20px;

    font-size: 12px;

    white-space: nowrap;

}


/* =========================================
   ANNOUNCEMENT MESSAGE
========================================= */

.announcement-message {

    margin-left: 57px;

    color: #555;

    font-size: 15px;

    line-height: 1.7;

    white-space: pre-line;

}


/* =========================================
   LATEST BADGE
========================================= */

.latest-badge {

    display: inline-block;

    margin-top: 18px;

    margin-left: 57px;

    padding: 5px 11px;

    background: #fff3cd;

    color: #856404;

    border-radius: 15px;

    font-size: 12px;

    font-weight: bold;

}


/* =========================================
   NO ANNOUNCEMENTS
========================================= */

.no-announcements {

    background: white;

    max-width: 700px;

    padding: 55px 30px;

    text-align: center;

    border-radius: 15px;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);

}


.no-icon {

    font-size: 55px;

    margin-bottom: 15px;

}


.no-announcements h2 {

    color: #0b3d91;

    margin-bottom: 10px;

}


.no-announcements p {

    color: #777;

    line-height: 1.6;

}


/* =========================================
   BACK BUTTON
========================================= */

.back-btn {

    display: inline-block;

    margin-top: 10px;

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


    .announcement-header {

        flex-direction: column;

    }


    .announcement-date {

        align-self: flex-start;

    }


    .announcement-message {

        margin-left: 0;

        margin-top: 15px;

    }


    .latest-badge {

        margin-left: 0;

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


    <a
        href="announcements.php"
        class="active"
    >

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


    <h1>

        📢 Announcements

    </h1>


    <p class="subtitle">

        Stay updated with the latest news,
        notices, and important information
        from the Alumni Portal.

    </p>


    <div class="announcement-container">


    <?php

    if (
        $result &&
        mysqli_num_rows($result) > 0
    ) {

        $counter = 0;


        while (
            $row =
            mysqli_fetch_assoc($result)
        ) {

            $counter++;


            /* Format Date */

            $formatted_date =
                date(
                    "d M Y, h:i A",
                    strtotime(
                        $row['created_at']
                    )
                );

    ?>


        <!-- =================================
             ANNOUNCEMENT CARD
        ================================== -->

        <div class="announcement-card">


            <div class="announcement-header">


                <div class="announcement-title">


                    <div class="announcement-icon">

                        📢

                    </div>


                    <h2>

                        <?php

                        echo htmlspecialchars(
                            $row['title']
                        );

                        ?>

                    </h2>


                </div>


                <span class="announcement-date">

                    📅

                    <?php

                    echo $formatted_date;

                    ?>

                </span>


            </div>


            <div class="announcement-message">

                <?php

                echo nl2br(
                    htmlspecialchars(
                        $row['message']
                    )
                );

                ?>

            </div>


            <?php

            /* Show Latest badge
               on the first announcement */

            if ($counter == 1) {

            ?>

                <span class="latest-badge">

                    🔔 Latest Announcement

                </span>

            <?php

            }

            ?>


        </div>


    <?php

        }

    }

    else {

    ?>


        <!-- =================================
             NO ANNOUNCEMENTS
        ================================== -->

        <div class="no-announcements">


            <div class="no-icon">

                📢

            </div>


            <h2>

                No Announcements Yet

            </h2>


            <p>

                There are currently no announcements
                available.

                Please check back later for
                important updates and news.

            </p>


        </div>


    <?php

    }

    ?>


    </div>


    <!-- BACK BUTTON -->

    <a
        href="dashboard.php"
        class="back-btn"
    >

        ← Back to Dashboard

    </a>


</div>


</body>

</html>