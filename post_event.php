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
   POST EVENT
========================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $event_name = trim($_POST['event_name'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $venue = trim($_POST['venue'] ?? '');
    $description = trim($_POST['description'] ?? '');


    /* Check empty fields */

    if (empty($event_name) || empty($event_date) || empty($venue) || empty($description)) {

        $error = "Please fill in all fields.";

    } else {

        /* Secure database insertion */

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO events
            (event_name, event_date, venue, description)
            VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $event_name,
            $event_date,
            $venue,
            $description
        );


        if (mysqli_stmt_execute($stmt)) {

            echo "<script>
                    alert('Event Posted Successfully!');
                    window.location='dashboard.php';
                  </script>";

            exit();

        } else {

            $error = "Error posting event: " . mysqli_error($conn);

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

<title>Post Event | Alumni Portal</title>


<style>

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
   EVENT FORM BOX
========================================= */

.event-box {

    background: white;

    width: 100%;

    max-width: 650px;

    padding: 30px;

    margin-top: 30px;

    border-radius: 12px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.08);

}


.event-box label {

    display: block;

    margin-top: 18px;

    margin-bottom: 8px;

    font-weight: bold;

    color: #334155;

}


.event-box input,

.event-box textarea {

    width: 100%;

    padding: 13px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    font-size: 15px;

    outline: none;

}


.event-box input:focus,

.event-box textarea:focus {

    border-color: #0b3d91;

    box-shadow: 0 0 0 3px rgba(11,61,145,0.1);

}


.event-box textarea {

    height: 150px;

    resize: vertical;

}


/* =========================================
   POST BUTTON
========================================= */

.post-btn {

    margin-top: 22px;

    padding: 13px 28px;

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
   ERROR MESSAGE
========================================= */

.error-message {

    max-width: 650px;

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


    <a href="post_job.php">

        💼 Post Job

    </a>


    <a href="post_event.php" class="active">

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


    <h1>📅 Post New Event</h1>


    <p class="subtitle">

        Create an event and share it with students and alumni.

    </p>


    <?php if (isset($error)): ?>

        <div class="error-message">

            <?php echo htmlspecialchars($error); ?>

        </div>

    <?php endif; ?>


    <div class="event-box">


        <form method="POST">


            <label>

                Event Name

            </label>


            <input

                type="text"

                name="event_name"

                placeholder="Enter event name"

                required

            >


            <label>

                Event Date

            </label>


            <input

                type="date"

                name="event_date"

                required

            >


            <label>

                Venue

            </label>


            <input

                type="text"

                name="venue"

                placeholder="Enter event venue"

                required

            >


            <label>

                Description

            </label>


            <textarea

                name="description"

                placeholder="Write details about the event..."

                required

            ></textarea>


            <button

                type="submit"

                class="post-btn"

            >

                📅 Post Event

            </button>


        </form>


    </div>


</div>


</body>

</html>