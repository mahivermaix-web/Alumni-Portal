<?php
session_start();
include("../database/connection.php");

/* =========================
   ADMIN SECURITY CHECK
========================= */

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'admin'
) {
    header("Location: ../login.php");
    exit();
}


/* =========================
   DELETE JOB
========================= */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $delete_query = "DELETE FROM jobs WHERE id = $id";

    mysqli_query($conn, $delete_query);

    header("Location: manage_jobs.php");
    exit();
}


/* =========================
   SEARCH JOBS
========================= */

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$search_safe = mysqli_real_escape_string($conn, $search);


/* =========================
   GET JOBS
========================= */

if (!empty($search_safe)) {

    $sql = "SELECT * FROM jobs
            WHERE job_title LIKE '%$search_safe%'
            OR company LIKE '%$search_safe%'
            OR location LIKE '%$search_safe%'
            ORDER BY id DESC";

} else {

    $sql = "SELECT * FROM jobs ORDER BY id DESC";

}

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Jobs - Admin Panel</title>

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
    color: #222;
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

    background: #0b3d91;

    padding-top: 30px;

    box-shadow: 3px 0 15px rgba(0,0,0,0.1);
}


.sidebar h2 {
    color: white;

    text-align: center;

    margin-bottom: 30px;

    font-size: 26px;
}


.sidebar a {
    display: block;

    color: white;

    text-decoration: none;

    padding: 15px 25px;

    font-size: 16px;

    transition: 0.3s;
}


.sidebar a:hover {
    background: #1456c4;

    padding-left: 32px;
}


/* ACTIVE LINK */

.sidebar a.active {
    background: #1456c4;

    border-left: 4px solid #ffc107;
}


/* =========================
   MAIN CONTENT
========================= */

.main-content {
    margin-left: 250px;

    padding: 40px;

    min-height: 100vh;
}


.page-header {
    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 30px;
}


.page-header h1 {
    color: #0b3d91;

    font-size: 32px;
}


.page-header p {
    color: #666;

    margin-top: 7px;
}


/* =========================
   SEARCH BOX
========================= */

.search-box {
    background: white;

    padding: 20px;

    border-radius: 12px;

    box-shadow: 0 5px 15px rgba(0,0,0,0.08);

    margin-bottom: 25px;
}


.search-form {
    display: flex;

    gap: 10px;
}


.search-form input {
    flex: 1;

    padding: 12px 15px;

    border: 1px solid #ddd;

    border-radius: 7px;

    font-size: 15px;

    outline: none;
}


.search-form input:focus {
    border-color: #0b3d91;

    box-shadow: 0 0 0 3px rgba(11,61,145,0.1);
}


.search-btn {
    padding: 12px 22px;

    background: #0b3d91;

    color: white;

    border: none;

    border-radius: 7px;

    cursor: pointer;

    font-size: 15px;

    font-weight: bold;
}


.search-btn:hover {
    background: #1456c4;
}


.clear-btn {
    padding: 12px 20px;

    background: #e9ecef;

    color: #333;

    text-decoration: none;

    border-radius: 7px;

    font-weight: bold;
}


.clear-btn:hover {
    background: #d6d8db;
}


/* =========================
   TABLE CARD
========================= */

.table-card {
    background: white;

    border-radius: 12px;

    padding: 20px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.08);

    overflow: hidden;
}


.table-container {
    overflow-x: auto;
}


table {
    width: 100%;

    border-collapse: collapse;

    min-width: 900px;
}


th {
    background: #0b3d91;

    color: white;

    padding: 15px;

    text-align: left;

    font-size: 14px;
}


td {
    padding: 14px 15px;

    border-bottom: 1px solid #eee;

    vertical-align: top;

    font-size: 14px;
}


tr:hover {
    background: #f7f9fc;
}


/* =========================
   JOB TITLE
========================= */

.job-title {
    font-weight: bold;

    color: #0b3d91;
}


.company {
    font-weight: 600;

    color: #333;
}


/* =========================
   SALARY
========================= */

.salary {
    color: #198754;

    font-weight: bold;
}


/* =========================
   DESCRIPTION
========================= */

.description {
    max-width: 300px;

    line-height: 1.5;

    color: #555;
}


/* =========================
   DELETE BUTTON
========================= */

.delete-btn {
    display: inline-block;

    background: #dc3545;

    color: white;

    padding: 8px 13px;

    text-decoration: none;

    border-radius: 6px;

    font-size: 13px;

    font-weight: bold;

    transition: 0.3s;
}


.delete-btn:hover {
    background: #b02a37;

    transform: translateY(-1px);
}


/* =========================
   NO JOBS
========================= */

.no-jobs {
    text-align: center;

    padding: 40px;

    color: #777;

    font-size: 17px;
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 800px) {

    .sidebar {
        width: 200px;
    }

    .main-content {
        margin-left: 200px;

        padding: 25px;
    }

    .page-header {
        display: block;
    }

    .search-form {
        flex-direction: column;
    }

}


@media (max-width: 600px) {

    .sidebar {
        position: relative;

        width: 100%;

        height: auto;

        padding-bottom: 15px;
    }

    .main-content {
        margin-left: 0;

        padding: 20px;
    }

    .page-header h1 {
        font-size: 26px;
    }

}

</style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <h2>Admin Panel</h2>

    <a href="dashboard.php">
        🏠 Dashboard
    </a>

    <a href="manage_users.php">
        👥 Manage Users
    </a>

    <a href="manage_jobs.php" class="active">
        💼 Manage Jobs
    </a>

    <a href="manage_events.php">
        📅 Manage Events
    </a>

    <a href="manage_announcements.php">
        📢 Manage Announcements
    </a>

    <a href="messages.php">
        📩 Contact Messages
    </a>

    <a href="../qr_scanner.php">
        📷 QR Scanner
    </a>

    <a href="../logout.php">
        🚪 Logout
    </a>

</div>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <div class="page-header">

        <div>

            <h1>💼 Manage Jobs</h1>

            <p>
                View and manage all job opportunities posted by alumni.
            </p>

        </div>

    </div>


    <!-- =========================
         SEARCH
    ========================= -->

    <div class="search-box">

        <form
            method="GET"
            action="manage_jobs.php"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                placeholder="Search by job title, company or location..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button
                type="submit"
                class="search-btn"
            >
                🔍 Search
            </button>

            <?php if (!empty($search)) { ?>

                <a
                    href="manage_jobs.php"
                    class="clear-btn"
                >
                    Clear
                </a>

            <?php } ?>

        </form>

    </div>


    <!-- =========================
         JOB TABLE
    ========================= -->

    <div class="table-card">

        <div class="table-container">

            <table>

                <tr>

                    <th>ID</th>

                    <th>Job Title</th>

                    <th>Company</th>

                    <th>Location</th>

                    <th>Salary</th>

                    <th>Description</th>

                    <th>Action</th>

                </tr>


                <?php

                if ($result && mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                ?>

                <tr>

                    <td>
                        <?php
                        echo htmlspecialchars($row['id']);
                        ?>
                    </td>


                    <td class="job-title">

                        <?php
                        echo htmlspecialchars($row['job_title']);
                        ?>

                    </td>


                    <td class="company">

                        <?php
                        echo htmlspecialchars($row['company']);
                        ?>

                    </td>


                    <td>

                        📍
                        <?php
                        echo htmlspecialchars($row['location']);
                        ?>

                    </td>


                    <td class="salary">

                        <?php
                        echo htmlspecialchars($row['salary']);
                        ?>

                    </td>


                    <td class="description">

                        <?php
                        echo nl2br(
                            htmlspecialchars(
                                $row['description']
                            )
                        );
                        ?>

                    </td>


                    <td>

                        <a
                            href="manage_jobs.php?delete=<?php echo $row['id']; ?>"
                            class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this job posting?');"
                        >
                            🗑️ Delete
                        </a>

                    </td>

                </tr>


                <?php

                    }

                } else {

                ?>


                <tr>

                    <td
                        colspan="7"
                        class="no-jobs"
                    >

                        <?php

                        if (!empty($search)) {

                            echo "No jobs found matching your search.";

                        } else {

                            echo "No job postings found.";

                        }

                        ?>

                    </td>

                </tr>


                <?php

                }

                ?>

            </table>

        </div>

    </div>


</div>


</body>

</html>