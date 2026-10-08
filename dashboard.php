<?php
session_start();

/* Check if student is logged in */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

/* Get student name safely */
$student_name = $_SESSION['name'] ?? 'Student';
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Dashboard | Alumni Portal</title>

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
    background: #f4f7fb;
    color: #1e293b;
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

    box-shadow: 4px 0 15px rgba(0,0,0,0.12);

    overflow-y: auto;
}


/* Logo */

.logo {
    text-align: center;

    color: white;

    font-size: 24px;
    font-weight: bold;

    margin-bottom: 35px;

    padding-bottom: 20px;

    border-bottom: 1px solid rgba(255,255,255,0.2);
}


/* Sidebar Links */

.sidebar a {
    display: flex;
    align-items: center;

    gap: 12px;

    color: #ffffff;

    text-decoration: none;

    padding: 14px 18px;

    margin-bottom: 7px;

    border-radius: 8px;

    font-size: 15px;

    transition: all 0.3s ease;
}


/* Hover */

.sidebar a:hover {
    background: rgba(255,255,255,0.15);

    transform: translateX(4px);
}


/* Active Dashboard */

.sidebar a.active {
    background: #ffffff;

    color: #0b3d91;

    font-weight: bold;

    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}


/* Logout */

.sidebar a.logout {
    margin-top: 25px;

    background: rgba(255,255,255,0.1);
}

.sidebar a.logout:hover {
    background: #dc2626;

    color: white;
}


/* =========================
   MAIN CONTENT
========================= */

.main-content {

    margin-left: 250px;

    min-height: 100vh;

    padding: 35px 45px;
}


/* =========================
   TOP HEADER
========================= */

.top-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    background: white;

    padding: 20px 25px;

    border-radius: 12px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.07);

    margin-bottom: 30px;
}


.top-header h1 {

    color: #0b3d91;

    font-size: 28px;

}


.top-header p {

    color: #64748b;

    margin-top: 5px;

}


/* User Profile */

.user-profile {

    display: flex;

    align-items: center;

    gap: 12px;
}


.user-icon {

    width: 45px;

    height: 45px;

    border-radius: 50%;

    background: #0b3d91;

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 20px;

    font-weight: bold;
}


/* =========================
   WELCOME CARD
========================= */

.welcome-card {

    background: linear-gradient(135deg, #0b3d91, #1456c4);

    color: white;

    padding: 35px;

    border-radius: 15px;

    margin-bottom: 30px;

    box-shadow: 0 8px 25px rgba(11,61,145,0.25);
}


.welcome-card h2 {

    font-size: 28px;

    margin-bottom: 12px;

}


.welcome-card p {

    font-size: 16px;

    line-height: 1.6;

    color: #e5efff;

}


/* =========================
   QUICK ACTIONS
========================= */

.section-title {

    color: #0b3d91;

    font-size: 22px;

    margin-bottom: 18px;
}


.quick-actions {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 20px;

    margin-bottom: 35px;
}


.action-card {

    background: white;

    padding: 25px;

    border-radius: 12px;

    text-decoration: none;

    color: #1e293b;

    box-shadow: 0 5px 18px rgba(0,0,0,0.07);

    transition: all 0.3s ease;
}


.action-card:hover {

    transform: translateY(-5px);

    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}


.action-icon {

    font-size: 30px;

    margin-bottom: 15px;
}


.action-card h3 {

    color: #0b3d91;

    margin-bottom: 8px;
}


.action-card p {

    color: #64748b;

    font-size: 14px;

    line-height: 1.5;
}


/* =========================
   INFORMATION CARDS
========================= */

.info-grid {

    display: grid;

    grid-template-columns: repeat(2, 1fr);

    gap: 25px;
}


.info-card {

    background: white;

    padding: 25px;

    border-radius: 12px;

    box-shadow: 0 5px 18px rgba(0,0,0,0.07);
}


.info-card h3 {

    color: #0b3d91;

    margin-bottom: 12px;
}


.info-card p {

    color: #64748b;

    line-height: 1.6;
}


/* =========================
   RESPONSIVE DESIGN
========================= */

@media (max-width: 1000px) {

    .quick-actions {

        grid-template-columns: repeat(2, 1fr);

    }

}


@media (max-width: 768px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;

    }


    .main-content {

        margin-left: 0;

        padding: 20px;

    }


    .top-header {

        flex-direction: column;

        align-items: flex-start;

        gap: 15px;

    }


    .quick-actions {

        grid-template-columns: 1fr;

    }


    .info-grid {

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <div class="logo">
        🎓 Alumni Portal
    </div>


    <a href="dashboard.php" class="active">
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
        👥 Alumni Directory
    </a>

    <a href="profile.php">
        👤 My Profile
    </a>


    <a href="view_jobs.php">
        💼 Jobs
    </a>


    <a href="my_applications.php">📩 My Applications</a>


    <a href="../logout.php" class="logout">
        🚪 Logout
    </a>

</div>



<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <!-- TOP HEADER -->

    <div class="top-header">

        <div>

            <h1>
                Welcome, <?php echo htmlspecialchars($student_name); ?> 👋
            </h1>

            <p>
                Welcome back to your Alumni Portal dashboard.
            </p>

        </div>


        <div class="user-profile">

            <div class="user-icon">

                <?php
                echo strtoupper(substr($student_name, 0, 1));
                ?>

            </div>

            <strong>
                <?php echo htmlspecialchars($student_name); ?>
            </strong>

        </div>

    </div>



    <!-- WELCOME CARD -->

    <div class="welcome-card">

        <h2>
            Student Dashboard
        </h2>

        <p>
            Connect with alumni, discover career opportunities,
            explore upcoming events, view announcements,
            and build your professional network through the Alumni Portal.
        </p>

    </div>



    <!-- QUICK ACTIONS -->

    <h2 class="section-title">
        Quick Access
    </h2>


    <div class="quick-actions">


        <a href="alumni_directory.php" class="action-card">

            <div class="action-icon">
                👥
            </div>

            <h3>
                Alumni Directory
            </h3>

            <p>
                Find and connect with alumni from your college.
            </p>

        </a>



        <a href="view_jobs.php" class="action-card">

            <div class="action-icon">
                💼
            </div>

            <h3>
                Job Opportunities
            </h3>

            <p>
                Explore job opportunities and career openings.
            </p>

        </a>



        <a href="view_events.php" class="action-card">

            <div class="action-icon">
                📅
            </div>

            <h3>
                Upcoming Events
            </h3>

            <p>
                View alumni events, reunions, and activities.
            </p>

        </a>

    </div>



    <!-- INFORMATION SECTION -->

    <h2 class="section-title">
        Explore Alumni Portal
    </h2>


    <div class="info-grid">


        <div class="info-card">

            <h3>
                📢 Latest Announcements
            </h3>

            <p>
                Stay updated with important announcements,
                college news, and alumni activities.
            </p>

            <br>

            <a href="announcements.php">
                View Announcements →
            </a>

        </div>



        <div class="info-card">

            <h3>
                👤 Your Profile
            </h3>

            <p>
                Keep your personal and academic information
                updated so alumni can connect with you.
            </p>

            <br>

            <a href="profile.php">
                View My Profile →
            </a>

        </div>


    </div>


</div>


</body>

</html>