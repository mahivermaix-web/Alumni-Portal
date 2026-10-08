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
   SEARCH
========================================= */

$search = trim($_GET['search'] ?? '');


/* =========================================
   GET STUDENTS
========================================= */

if (!empty($search)) {

    $searchTerm = "%" . $search . "%";

    $stmt = mysqli_prepare(
        $conn,
        "SELECT full_name, email, department,
                graduation_year, phone
         FROM users
         WHERE role = 'student'
         AND (
             full_name LIKE ?
             OR email LIKE ?
             OR department LIKE ?
         )
         ORDER BY full_name ASC"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

} else {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT full_name, email, department,
                graduation_year, phone
         FROM users
         WHERE role = 'student'
         ORDER BY full_name ASC"
    );

}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>View Students | Alumni Portal</title>


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


.logo {

    text-align: center;

    color: white;

    font-size: 23px;

    font-weight: bold;

    margin-bottom: 35px;

    padding-bottom: 20px;

    border-bottom: 1px solid rgba(255,255,255,0.2);

}


.sidebar a {

    display: block;

    color: white;

    text-decoration: none;

    padding: 14px 18px;

    margin-bottom: 7px;

    border-radius: 8px;

    font-size: 15px;

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


.sidebar a.logout {

    margin-top: 25px;

}


.sidebar a.logout:hover {

    background: #dc2626;

    color: white;

}


/* =========================================
   MAIN CONTENT
========================================= */

.main-content {

    margin-left: 250px;

    padding: 40px;

    min-height: 100vh;

}


/* =========================================
   HEADER
========================================= */

.page-header {

    background: white;

    padding: 25px 30px;

    border-radius: 12px;

    box-shadow: 0 5px 18px rgba(0,0,0,0.07);

    margin-bottom: 25px;

}


.page-header h1 {

    color: #0b3d91;

    margin-bottom: 8px;

}


.page-header p {

    color: #64748b;

}


/* =========================================
   SEARCH BOX
========================================= */

.search-container {

    background: white;

    padding: 20px;

    border-radius: 12px;

    box-shadow: 0 5px 18px rgba(0,0,0,0.07);

    margin-bottom: 25px;

}


.search-form {

    display: flex;

    gap: 10px;

}


.search-form input {

    flex: 1;

    padding: 13px 15px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    font-size: 15px;

    outline: none;

}


.search-form input:focus {

    border-color: #0b3d91;

    box-shadow: 0 0 0 3px rgba(11,61,145,0.1);

}


.search-btn {

    padding: 13px 25px;

    background: #0b3d91;

    color: white;

    border: none;

    border-radius: 7px;

    font-weight: bold;

    cursor: pointer;

}


.search-btn:hover {

    background: #1456c4;

}


.clear-btn {

    padding: 13px 20px;

    background: #e2e8f0;

    color: #334155;

    text-decoration: none;

    border-radius: 7px;

    font-weight: bold;

}


.clear-btn:hover {

    background: #cbd5e1;

}


/* =========================================
   TABLE CONTAINER
========================================= */

.table-container {

    background: white;

    border-radius: 12px;

    padding: 25px;

    box-shadow: 0 5px 18px rgba(0,0,0,0.07);

    overflow-x: auto;

}


/* =========================================
   TABLE
========================================= */

table {

    width: 100%;

    border-collapse: collapse;

}


thead {

    background: #0b3d91;

    color: white;

}


th {

    padding: 15px;

    text-align: left;

    font-size: 14px;

}


td {

    padding: 15px;

    border-bottom: 1px solid #e2e8f0;

    font-size: 14px;

}


tbody tr:hover {

    background: #f8fafc;

}


/* =========================================
   STUDENT NAME
========================================= */

.student-name {

    font-weight: bold;

    color: #0b3d91;

}


/* =========================================
   NO RESULTS
========================================= */

.no-results {

    text-align: center;

    padding: 40px;

    color: #64748b;

    font-size: 16px;

}


/* =========================================
   RESPONSIVE
========================================= */

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


    .search-form {

        flex-direction: column;

    }


    .search-btn,
    .clear-btn {

        text-align: center;

        width: 100%;

    }

}

</style>

</head>


<body>


<!-- =========================================
     SIDEBAR
========================================= -->

<div class="sidebar">


    <div class="logo">

        🎓 Alumni Portal

    </div>


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


    <a href="view_events.php">

        📅 View Events

    </a>


    <a href="view_students.php" class="active">

        🎓 View Students

    </a>


    <a href="../logout.php" class="logout">

        🚪 Logout

    </a>


</div>



<!-- =========================================
     MAIN CONTENT
========================================= -->

<div class="main-content">


    <!-- HEADER -->

    <div class="page-header">

        <h1>

            🎓 Student Directory

        </h1>


        <p>

            View registered students and connect with the student community.

        </p>

    </div>



    <!-- SEARCH -->

    <div class="search-container">


        <form
            method="GET"
            action="view_students.php"
            class="search-form"
        >


            <input

                type="text"

                name="search"

                placeholder="Search by name, email, or department..."

                value="<?php

                echo htmlspecialchars($search);

                ?>"

            >


            <button
                type="submit"
                class="search-btn"
            >

                🔍 Search

            </button>


            <?php if (!empty($search)): ?>

                <a
                    href="view_students.php"
                    class="clear-btn"
                >

                    Clear

                </a>

            <?php endif; ?>


        </form>


    </div>



    <!-- STUDENT TABLE -->

    <div class="table-container">


        <table>


            <thead>

                <tr>

                    <th>Student Name</th>

                    <th>Email</th>

                    <th>Department</th>

                    <th>Graduation Year</th>

                    <th>Phone</th>

                </tr>

            </thead>


            <tbody>


            <?php

            if (mysqli_num_rows($result) > 0):

                while ($student = mysqli_fetch_assoc($result)):

            ?>


                <tr>


                    <td class="student-name">

                        🎓

                        <?php

                        echo htmlspecialchars(
                            $student['full_name'] ?? 'Not Provided'
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $student['email'] ?? 'Not Provided'
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $student['department'] ?? 'Not Provided'
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $student['graduation_year'] ?? 'Not Provided'
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $student['phone'] ?? 'Not Provided'
                        );

                        ?>

                    </td>


                </tr>


            <?php

                endwhile;

            else:

            ?>


                <tr>

                    <td
                        colspan="5"
                        class="no-results"
                    >

                        😔 No students found.

                    </td>

                </tr>


            <?php

            endif;

            ?>


            </tbody>


        </table>


    </div>


</div>


</body>

</html>