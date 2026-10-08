<?php
session_start();

include("../database/connection.php");


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


$search = trim($_GET['search'] ?? '');


$sql = "
    SELECT
        id,
        company,
        job_title,
        description,
        location,
        salary
    FROM jobs
";

if ($search != '') {

    $safe_search = mysqli_real_escape_string(
        $conn,
        $search
    );

    $sql .= "
        WHERE
            job_title LIKE '%$safe_search%'
            OR company LIKE '%$safe_search%'
            OR location LIKE '%$safe_search%'
    ";
}


$sql .= "
    ORDER BY id DESC
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

<title>Job Opportunities - Student Portal</title>


<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}


body {
    background: #f4f6f9;
}


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

    display: flex;

    gap: 15px;

}


.search-input {

    flex: 1;

    padding: 14px 16px;

    border: 1px solid #d5d9e0;

    border-radius: 8px;

    font-size: 15px;

    outline: none;

}


.search-input:focus {

    border-color: #0b3d91;

    box-shadow:
        0 0 0 3px
        rgba(11,61,145,0.1);

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


.jobs-header {

    max-width: 1100px;

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 20px;

}


.jobs-header h2 {

    color: #0b3d91;

    font-size: 23px;

}


.job-count {

    background: #e8f0ff;

    color: #0b3d91;

    padding: 8px 15px;

    border-radius: 20px;

    font-size: 13px;

    font-weight: bold;

}

.jobs-grid {

    max-width: 1100px;

    display: grid;

    grid-template-columns:
        repeat(auto-fit, minmax(320px, 1fr));

    gap: 25px;

}


.job-card {

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


.job-card:hover {

    transform: translateY(-6px);

    box-shadow:
        0 12px 30px
        rgba(0,0,0,0.14);

}


.job-top {

    display: flex;

    align-items: center;

    gap: 15px;

    margin-bottom: 20px;

}


.company-icon {

    width: 58px;

    height: 58px;

    border-radius: 12px;

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


.job-top h3 {

    color: #0b3d91;

    font-size: 20px;

    margin-bottom: 5px;

}


.company-name {

    color: #777;

    font-size: 14px;

}


.job-details {

    border-top: 1px solid #eee;

    padding-top: 18px;

}


.job-detail {

    display: flex;

    gap: 10px;

    margin-bottom: 13px;

    color: #555;

    font-size: 14px;

    line-height: 1.5;

}


.job-icon {

    min-width: 22px;

}

.job-description {

    background: #f7f9fc;

    padding: 15px;

    margin-top: 18px;

    border-radius: 8px;

    color: #555;

    font-size: 14px;

    line-height: 1.6;

}


.job-description strong {

    display: block;

    color: #0b3d91;

    margin-bottom: 7px;

}



.apply-btn {

    display: block;

    text-align: center;

    margin-top: 20px;

    padding: 12px;

    background: #0b3d91;

    color: white;

    text-decoration: none;

    border-radius: 7px;

    font-weight: bold;

    transition: 0.3s;

}


.apply-btn:hover {

    background: #1456c4;

    transform: translateY(-2px);

}


.empty-state {

    max-width: 700px;

    background: white;

    padding: 55px 30px;

    text-align: center;

    border-radius: 15px;

    box-shadow:
        0 8px 25px
        rgba(0,0,0,0.08);

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

        flex-direction: column;

    }


    .jobs-header {

        flex-direction: column;

        align-items: flex-start;

        gap: 10px;

    }


    .jobs-grid {

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


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

    <a href="profile.php">

        👤 My Profile

    </a>


    <a
        href="view_jobs.php"
        class="active"
    >

        💼 Jobs

    </a>


    <a href="../logout.php">

        🚪 Logout

    </a>

</div>


<div class="main-content">


    <h1>

        💼 Job Opportunities

    </h1>


    <p class="subtitle">

        Explore career opportunities and job
        openings shared by our alumni community.

    </p>


    <div class="search-container">


        <form
            method="GET"
            action="view_jobs.php"
            class="search-form"
        >


            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="🔍 Search by job title, company or location..."
                value="<?php
                    echo htmlspecialchars($search);
                ?>"
            >


            <button
                type="submit"
                class="search-btn"
            >

                Search Jobs

            </button>


        </form>


        <?php

        if ($search != '') {

        ?>

            <a
                href="view_jobs.php"
                class="clear-btn"
            >

                ✕ Clear Search

            </a>

        <?php

        }

        ?>

    </div>


    <?php

    $total_jobs = 0;

    if ($result) {

        $total_jobs =
            mysqli_num_rows($result);

    }

    ?>


    <div class="jobs-header">


        <h2>

            Available Opportunities

        </h2>


        <span class="job-count">

            <?php
            echo $total_jobs;
            ?>

            Jobs Found

        </span>


    </div>

    <?php

    if (
        $result &&
        mysqli_num_rows($result) > 0
    ) {

    ?>


        <div class="jobs-grid">


        <?php

        while (
            $row =
            mysqli_fetch_assoc($result)
        ) {

            $company =
                $row['company']
                ?? 'Company';


            $first_letter =
                strtoupper(
                    substr(
                        trim($company),
                        0,
                        1
                    )
                );

        ?>


            <div class="job-card">


                <!-- JOB TOP -->

                <div class="job-top">


                    <div class="company-icon">

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
                                $row['job_title']
                            );

                            ?>

                        </h3>


                        <p class="company-name">

                            🏢

                            <?php

                            echo htmlspecialchars(
                                $row['company']
                            );

                            ?>

                        </p>


                    </div>


                </div>


                <div class="job-details">


                    <?php

                    if (
                        !empty(
                            $row['location']
                        )
                    ) {

                    ?>

                    <div class="job-detail">

                        <span class="job-icon">

                            📍

                        </span>

                        <span>

                            <strong>
                                Location:
                            </strong>

                            <?php

                            echo htmlspecialchars(
                                $row['location']
                            );

                            ?>

                        </span>

                    </div>

                    <?php

                    }


                    if (
                        !empty(
                            $row['salary']
                        )
                    ) {

                    ?>

                    <div class="job-detail">

                        <span class="job-icon">

                            💰

                        </span>

                        <span>

                            <strong>
                                Salary:
                            </strong>

                            <?php

                            echo htmlspecialchars(
                                $row['salary']
                            );

                            ?>

                        </span>

                    </div>

                    <?php

                    }

                    ?>


                </div>

                <?php

                if (
                    !empty(
                        $row['description']
                    )
                ) {

                ?>

                <div class="job-description">


                    <strong>

                        📝 Job Description

                    </strong>


                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $row['description']
                        )
                    );

                    ?>


                </div>

                <?php

                }

                ?>

               <a href="apply_job.php?id=<?php echo $row['id']; ?>" class="apply-btn">
    📩 Contact / Apply
</a>


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

                💼

            </div>


            <h2>

                No Jobs Found

            </h2>


            <p>

                <?php

                if ($search != '') {

                    echo "No job opportunities matched your search. Try another keyword.";

                }
                else {

                    echo "There are currently no job opportunities available. Please check back later.";

                }

                ?>

            </p>


        </div>


    <?php

    }

    ?>

    <a
        href="dashboard.php"
        class="back-btn"
    >

        ← Back to Dashboard

    </a>


</div>


</body>

</html>