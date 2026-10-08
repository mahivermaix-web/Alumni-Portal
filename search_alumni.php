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
   SEARCH VALUES
========================================= */

$search = trim($_GET['search'] ?? '');

$department = trim($_GET['department'] ?? '');


/* =========================================
   GET DEPARTMENTS FOR FILTER
========================================= */

$department_query = "
    SELECT DISTINCT department
    FROM users
    WHERE role = 'alumni'
    AND department IS NOT NULL
    AND department != ''
    ORDER BY department ASC
";

$department_result = mysqli_query(
    $conn,
    $department_query
);


/* =========================================
   SEARCH ALUMNI
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


/* SEARCH BY NAME / COMPANY / JOB */

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


/* FILTER BY DEPARTMENT */

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


$result = mysqli_query(
    $conn,
    $sql
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Search Alumni - Student Portal</title>


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
   SEARCH BOX
========================================= */

.search-container {

    background: white;

    padding: 25px;

    border-radius: 15px;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);

    max-width: 1100px;

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


/* RESET BUTTON */

.reset-btn {

    display: inline-block;

    margin-top: 15px;

    color: #0b3d91;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;

}


.reset-btn:hover {

    text-decoration: underline;

}


/* =========================================
   RESULTS HEADER
========================================= */

.results-header {

    max-width: 1100px;

    margin-bottom: 20px;

    display: flex;

    justify-content: space-between;

    align-items: center;

}


.results-header h2 {

    color: #0b3d91;

    font-size: 22px;

}


.result-count {

    background: #e8f0ff;

    color: #0b3d91;

    padding: 7px 14px;

    border-radius: 20px;

    font-size: 13px;

    font-weight: bold;

}


/* =========================================
   ALUMNI GRID
========================================= */

.alumni-grid {

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(300px, 1fr));

    gap: 25px;

    max-width: 1100px;

}


/* =========================================
   ALUMNI CARD
========================================= */

.alumni-card {

    background: white;

    border-radius: 15px;

    padding: 25px;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);

    border: 1px solid #e8e8e8;

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
   ALUMNI HEADER
========================================= */

.alumni-header {

    display: flex;

    align-items: center;

    gap: 15px;

    margin-bottom: 22px;

}


.alumni-avatar {

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

    font-size: 25px;

    font-weight: bold;

}


.alumni-header h3 {

    color: #0b3d91;

    font-size: 19px;

    margin-bottom: 5px;

}


.alumni-header p {

    color: #777;

    font-size: 13px;

}


/* =========================================
   ALUMNI DETAILS
========================================= */

.alumni-details {

    border-top: 1px solid #eee;

    padding-top: 18px;

}


.detail-row {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    margin-bottom: 13px;

    font-size: 14px;

    color: #555;

    line-height: 1.5;

}


.detail-row strong {

    color: #0b3d91;

}


/* =========================================
   NO RESULTS
========================================= */

.no-results {

    background: white;

    max-width: 700px;

    padding: 50px;

    text-align: center;

    border-radius: 15px;

    box-shadow:
        0 8px 25px rgba(0,0,0,0.08);

}


.no-results-icon {

    font-size: 50px;

    margin-bottom: 15px;

}


.no-results h2 {

    color: #0b3d91;

    margin-bottom: 10px;

}


.no-results p {

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


    .search-form {

        grid-template-columns: 1fr;

    }


    .results-header {

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


    <a
        href="search_alumni.php"
        class="active"
    >

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


    <h1>Search Alumni</h1>


    <p class="subtitle">

        Find alumni by name, company, job title, or department
        and connect with your professional network.

    </p>


    <!-- =====================================
         SEARCH FORM
    ====================================== -->

    <div class="search-container">


        <form
            method="GET"
            action="search_alumni.php"
            class="search-form"
        >


            <!-- SEARCH -->

            <input

                type="text"

                name="search"

                class="search-input"

                placeholder="🔍 Search by name, company or job title..."

                value="<?php
                    echo htmlspecialchars($search);
                ?>"

            >


            <!-- DEPARTMENT -->

            <select
                name="department"
                class="department-select"
            >

                <option value="">
                    All Departments
                </option>


                <?php

                if (
                    $department_result &&
                    mysqli_num_rows(
                        $department_result
                    ) > 0
                ) {

                    while (
                        $dept =
                        mysqli_fetch_assoc(
                            $department_result
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


            <!-- SEARCH BUTTON -->

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
                href="search_alumni.php"
                class="reset-btn"
            >

                ✕ Clear Search

            </a>

        <?php

        }

        ?>

    </div>


    <!-- =====================================
         RESULTS HEADER
    ====================================== -->

    <div class="results-header">


        <h2>
            Alumni Network
        </h2>


        <?php

        $total_results = 0;

        if ($result) {

            $total_results =
                mysqli_num_rows($result);

        }

        ?>


        <span class="result-count">

            <?php
            echo $total_results;
            ?>

            Alumni Found

        </span>


    </div>


    <!-- =====================================
         ALUMNI RESULTS
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

            /* Get first letter */

            $name =
                $row['full_name']
                ?? 'A';

            $first_letter =
                strtoupper(
                    substr(
                        trim($name),
                        0,
                        1
                    )
                );

        ?>


            <!-- ALUMNI CARD -->

            <div class="alumni-card">


                <!-- HEADER -->

                <div class="alumni-header">


                    <div class="alumni-avatar">

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


                        <p>

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


                <!-- DETAILS -->

                <div class="alumni-details">


                    <!-- DEPARTMENT -->

                    <?php

                    if (
                        !empty(
                            $row['department']
                        )
                    ) {

                    ?>

                    <div class="detail-row">

                        <span>🎓</span>

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

                    ?>


                    <!-- GRADUATION -->

                    <?php

                    if (
                        !empty(
                            $row[
                                'graduation_year'
                            ]
                        )
                    ) {

                    ?>

                    <div class="detail-row">

                        <span>📅</span>

                        <div>

                            <strong>
                                Graduation:
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

                    ?>


                    <!-- COMPANY -->

                    <?php

                    if (
                        !empty(
                            $row['company']
                        )
                    ) {

                    ?>

                    <div class="detail-row">

                        <span>🏢</span>

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

                    ?>


                    <!-- JOB -->

                    <?php

                    if (
                        !empty(
                            $row['job_title']
                        )
                    ) {

                    ?>

                    <div class="detail-row">

                        <span>💼</span>

                        <div>

                            <strong>
                                Job:
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


        <!-- NO RESULTS -->

        <div class="no-results">


            <div class="no-results-icon">

                🔍

            </div>


            <h2>

                No Alumni Found

            </h2>


            <p>

                We couldn't find any alumni matching
                your search criteria.

                Try searching with a different name,
                company, job title, or department.

            </p>


        </div>


    <?php

    }

    ?>


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