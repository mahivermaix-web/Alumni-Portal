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
   POST ANNOUNCEMENT
========================================= */

if (isset($_POST['post_announcement'])) {

    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');

    /* Check empty fields */

    if (empty($title) || empty($message)) {

        $error = "Please fill in all fields.";

    } else {

        /* Secure database insertion */

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO announcements (title, message)
             VALUES (?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $title,
            $message
        );

        if (mysqli_stmt_execute($stmt)) {

            echo "<script>
                    alert('Announcement Posted Successfully!');
                    window.location='post_announcement.php';
                  </script>";

            exit();

        } else {

            $error = "Error posting announcement: " . mysqli_error($conn);

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

<title>Post Announcement | Alumni Portal</title>

<link rel="stylesheet" href="../css/dashboard.css">

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #f4f7fb;
    font-family: Arial, sans-serif;
}


/* =========================
   SIDEBAR
========================= */

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100vh;
    background: linear-gradient(180deg, #0b3d91, #082b68);
    padding: 25px 15px;
}

.sidebar h2 {
    color: white;
    text-align: center;
    margin-bottom: 30px;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 14px 18px;
    margin-bottom: 6px;
    border-radius: 8px;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.15);
}


/* =========================
   MAIN CONTENT
========================= */

.main-content {
    margin-left: 250px;
    padding: 40px;
}

.main-content h1 {
    color: #0b3d91;
    margin-bottom: 10px;
}

.subtitle {
    color: #64748b;
}


/* =========================
   ANNOUNCEMENT BOX
========================= */

.announcement-box {
    background: white;
    width: 100%;
    max-width: 650px;
    padding: 30px;
    margin-top: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}


.announcement-box label {
    display: block;
    margin-top: 18px;
    margin-bottom: 8px;
    font-weight: bold;
    color: #334155;
}


.announcement-box input,
.announcement-box textarea {
    width: 100%;
    padding: 13px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    font-size: 15px;
    outline: none;
}


.announcement-box input:focus,
.announcement-box textarea:focus {
    border-color: #0b3d91;
    box-shadow: 0 0 0 3px rgba(11,61,145,0.1);
}


.announcement-box textarea {
    height: 160px;
    resize: vertical;
}


/* =========================
   BUTTON
========================= */

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


/* =========================
   ERROR
========================= */

.error-message {
    max-width: 650px;
    background: #fee2e2;
    color: #991b1b;
    padding: 12px 15px;
    border-radius: 7px;
    margin-top: 20px;
}


/* =========================
   RESPONSIVE
========================= */

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

    .announcement-box {
        width: 100%;
    }

}

</style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

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


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">

    <h1>📢 Post Announcement</h1>

    <p class="subtitle">
        Share important news and updates with students and alumni.
    </p>


    <?php if (isset($error)): ?>

        <div class="error-message">

            <?php echo htmlspecialchars($error); ?>

        </div>

    <?php endif; ?>


    <div class="announcement-box">

        <form method="POST">


            <label>
                Announcement Title
            </label>

            <input
                type="text"
                name="title"
                placeholder="Enter announcement title"
                required
            >


            <label>
                Announcement Message
            </label>

            <textarea
                name="message"
                placeholder="Write your announcement here..."
                required
            ></textarea>


            <button
                type="submit"
                name="post_announcement"
                class="post-btn"
            >

                📢 Post Announcement

            </button>


        </form>

    </div>

</div>


</body>

</html>