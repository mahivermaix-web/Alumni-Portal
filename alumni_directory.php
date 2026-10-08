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
   SEARCH AND FILTER
========================================= */

$search = trim($_GET['search'] ?? '');
$department = trim($_GET['department'] ?? '');


/* =========================================
   GET DEPARTMENTS
========================================= */

$dept_sql = "
    SELECT DISTINCT department
    FROM users
    WHERE role = 'alumni'
    AND department IS NOT NULL
    AND department != ''
    ORDER BY department ASC
";

$dept_result = mysqli_query($conn, $dept_sql);


/* =========================================
   ALUMNI DIRECTORY QUERY
========================================= */

$sql = "
    SELECT
        id,
        full_name,
        email,
        department,
        graduation_year,
        phone,
        company,
        job_title
    FROM users
    WHERE role = 'alumni'
";


/* SEARCH */

if ($search != '') {

    $safe_search = mysqli_real_escape_string(
        $conn,
        $search
    );

    $sql .= "
        AND (
            full_name LIKE '%$safe_search%'
            OR company LIKE '%$safe_search%'
            OR job_title LIKE '%$safe_search%'
        )
    ";
}


/* DEPARTMENT FILTER */

if ($department != '') {

    $safe_department = mysqli_real_escape_string(
        $conn,
        $department
    );

    $sql .= "
        AND department = '$safe_department'
    ";
}


$sql .= "
    ORDER BY full_name ASC
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

<title>Alumni Directory - Student Portal</title>


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
   SEARCH AREA
========================================= */

.search-container {
    background: white;

    max-width: 1100px;

    padding: 25px;

    border-radius: 15px;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);

    margin-bottom: 35px;
}


.search-form {
    display: grid;

    grid-template-columns:
        2fr 1fr auto;

    gap: 15px;
}


.search-input,
.department-select {
    width: 100%;

    padding: 14px 16px;

    border: 1px solid #d5d9e0;

    border-radius: 8px;

    font-size: 15px;

    outline: none;

    background: white;
}


.search-input:focus,
.department-select:focus {
    border-color: #0b3d91;

    box-shadow:
        0 0 0 3px rgba(11,61,145,0.1);
}


.search-btn {
    padding: 14px 25px;

    background: #0b3d91;

    color: white;

    border: none;

    border-radius: 8px;

    font-size: 15px;

    font-weight: bold;

    cursor: pointer;

    transition: 0.3s;
}


.search-btn:hover {
    background: #1456c4;

    transform: translateY(-2px);
}


.clear-btn {
    display: inline-block;

    margin-top: 15px;

    color: #0b3d91;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;
}


.clear-btn:hover {
    text-decoration: underline;
}


/* =========================================
   DIRECTORY HEADER
========================================= */

.directory-header {
    max-width: 1100px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 20px;
}


.directory-header h2 {
    color: #0b3d91;

    font-size: 23px;
}


.alumni-count {
    background: #e8f0ff;

    color: #0b3d91;

    padding: 8px 15px;

    border-radius: 20px;

    font-size: 13px;

    font-weight: bold;
}


/* =========================================
   ALUMNI GRID
========================================= */

.alumni-grid {
    max-width: 1100px;

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(300px, 1fr));

    gap: 25px;
}


/* =========================================
   ALUMNI CARD
========================================= */

.alumni-card {
    background: white;

    border-radius: 15px;

    padding: 25px;

    border: 1px solid #e8e8e8;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);

    transition:
        transform 0.3s,
        box-shadow 0.3s;
}


.alumni-card:hover {
    transform: translateY(-6px);

    box-shadow:
        0 12px 30px rgba(0,0,0,0.14);
}


/* =========================================
   CARD TOP
========================================= */

.card-top {
    display: flex;

    align-items: center;

    gap: 15px;

    margin-bottom: 20px;
}


.avatar {
    width: 60px;

    height: 60px;

    border-radius: 50%;

    background:
        linear-gradient(
            135deg,
            #0b3d91,
            #1456c4
        );

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 24px;

    font-weight: bold;
}


.card-top h3 {
    color: #0b3d91;

    font-size: 19px;

    margin-bottom: 5px;
}


.role {
    color: #777;

    font-size: 13px;
}


/* =========================================
   CARD DETAILS
========================================= */

.card-details {
    border-top: 1px solid #eee;

    padding-top: 18px;
}


.detail {
    display: flex;

    gap: 10px;

    margin-bottom: 13px;

    color: #555;

    font-size: 14px;

    line-height: 1.5;
}


.detail-icon {
    min-width: 22px;
}


.detail strong {
    color: #0b3d91;
}


/* =========================================
   EMPTY STATE
========================================= */

.empty-state {
    max-width: 700px;

    background: white;

    padding: 55px 30px;

    text-align: center;

    border-radius: 15px;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);
}


.empty-icon {
    font-size: 55px;

    margin-bottom: 15px;
}


.empty-state h2 {
    color: #0b3d91;

    margin-bottom: 10px;
}


.empty-state p {
    color: #777;

    line-height: 1.6;
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


    .search-form {
        grid-template-columns: 1fr;
    }


    .directory-header {
        flex-direction: column;

        align-items: flex-start;

        gap: 10px;
    }


    .alumni-grid {
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


    <a href="view_events.php">
        📅 Events
    </a>


    <a href="search_alumni.php">
        🔍 Search Alumni
    </a>


    <a href="announcements.php">
        📢 Announcements
    </a>


    <a
        href="alumni_directory.php"
        class="active"
    >
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
        🎓 Alumni Directory
    </h1>


    <p class="subtitle">

        Explore our alumni community and discover
        professionals from different departments
        and graduating years.

    </p>


    <!-- =====================================
         SEARCH AND FILTER
    ====================================== -->

    <div class="search-container">


        <form
            method="GET"
            action="alumni_directory.php"
            class="search-form"
        >


            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="🔍 Search by name, company or job title..."
                value="<?php
                    echo htmlspecialchars($search);
                ?>"
            >


            <select
                name="department"
                class="department-select"
            >

                <option value="">
                    All Departments
                </option>


                <?php

                if (
                    $dept_result &&
                    mysqli_num_rows(
                        $dept_result
                    ) > 0
                ) {

                    while (
                        $dept =
                        mysqli_fetch_assoc(
                            $dept_result
                        )
                    ) {

                        $selected =
                            ($department ==
                            $dept['department'])
                            ? 'selected'
                            : '';

                ?>

                    <option
                        value="<?php
                            echo htmlspecialchars(
                                $dept['department']
                            );
                        ?>"
                        <?php echo $selected; ?>
                    >

                        <?php
                        echo htmlspecialchars(
                            $dept['department']
                        );
                        ?>

                    </option>

                <?php

                    }

                }

                ?>

            </select>


            <button
                type="submit"
                class="search-btn"
            >

                Search

            </button>


        </form>


        <?php

        if (
            $search != '' ||
            $department != ''
        ) {

        ?>

            <a
                href="alumni_directory.php"
                class="clear-btn"
            >

                ✕ Clear Search

            </a>

        <?php

        }

        ?>

    </div>


    <!-- =====================================
         DIRECTORY HEADER
    ====================================== -->

    <?php

    $total_alumni = 0;

    if ($result) {

        $total_alumni =
            mysqli_num_rows($result);

    }

    ?>


    <div class="directory-header">


        <h2>
            Alumni Community
        </h2>


        <span class="alumni-count">

            <?php
            echo $total_alumni;
            ?>

            Alumni Found

        </span>


    </div>


    <!-- =====================================
         ALUMNI CARDS
    ====================================== -->

    <?php

    if (
        $result &&
        mysqli_num_rows($result) > 0
    ) {

    ?>


        <div class="alumni-grid">


        <?php

        while (
            $row =
            mysqli_fetch_assoc($result)
        ) {

            $name =
                $row['full_name']
                ?? 'Alumni';


            $first_letter =
                strtoupper(
                    substr(
                        trim($name),
                        0,
                        1
                    )
                );

        ?>


            <div class="alumni-card">


                <!-- CARD TOP -->

                <div class="card-top">


                    <div class="avatar">

                        <?php
                        echo htmlspecialchars(
                            $first_letter
                        );
                        ?>

                    </div>


                    <div>

                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $row['full_name']
                            );
                            ?>

                        </h3>


                        <p class="role">

                            Alumni

                            <?php

                            if (
                                !empty(
                                    $row['department']
                                )
                            ) {

                                echo " • " .
                                    htmlspecialchars(
                                        $row[
                                            'department'
                                        ]
                                    );

                            }

                            ?>

                        </p>

                    </div>


                </div>


                <!-- CARD DETAILS -->

                <div class="card-details">


                    <?php

                    if (
                        !empty(
                            $row['department']
                        )
                    ) {

                    ?>

                    <div class="detail">

                        <span class="detail-icon">
                            🎓
                        </span>

                        <div>

                            <strong>
                                Department:
                            </strong>

                            <?php
                            echo htmlspecialchars(
                                $row[
                                    'department'
                                ]
                            );
                            ?>

                        </div>

                    </div>

                    <?php

                    }


                    if (
                        !empty(
                            $row[
                                'graduation_year'
                            ]
                        )
                    ) {

                    ?>

                    <div class="detail">

                        <span class="detail-icon">
                            📅
                        </span>

                        <div>

                            <strong>
                                Graduation Year:
                            </strong>

                            <?php
                            echo htmlspecialchars(
                                $row[
                                    'graduation_year'
                                ]
                            );
                            ?>

                        </div>

                    </div>

                    <?php

                    }


                    if (
                        !empty(
                            $row['company']
                        )
                    ) {

                    ?>

                    <div class="detail">

                        <span class="detail-icon">
                            🏢
                        </span>

                        <div>

                            <strong>
                                Company:
                            </strong>

                            <?php
                            echo htmlspecialchars(
                                $row[
                                    'company'
                                ]
                            );
                            ?>

                        </div>

                    </div>

                    <?php

                    }


                    if (
                        !empty(
                            $row['job_title']
                        )
                    ) {

                    ?>

                    <div class="detail">

                        <span class="detail-icon">
                            💼
                        </span>

                        <div>

                            <strong>
                                Job Title:
                            </strong>

                            <?php
                            echo htmlspecialchars(
                                $row[
                                    'job_title'
                                ]
                            );
                            ?>

                        </div>

                    </div>

                    <?php

                    }

                    ?>


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


        <div class="empty-state">


            <div class="empty-icon">
                🎓
            </div>


            <h2>
                No Alumni Found
            </h2>


            <p>

                No alumni matched your search criteria.
                Try searching for another name,
                company, job title, or department.

            </p>


        </div>


    <?php

    }

    ?>


    <!-- BACK TO DASHBOARD -->

    <a
        href="dashboard.php"
        class="back-btn"
    >

        ← Back to Dashboard

    </a>


</div>


</body>

</html>