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

$id = $_SESSION['user_id'];

$message = "";
$error = "";


/* =========================================
   UPDATE PROFILE
========================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $graduation_year = trim($_POST['graduation_year'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $job_title = trim($_POST['job_title'] ?? '');


    /* Check required fields */

    if (empty($full_name) || empty($email)) {

        $error = "Full Name and Email are required.";

    } else {

        /* Check whether email is already used by another user */

        $check_stmt = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ? AND id != ?"
        );

        mysqli_stmt_bind_param(
            $check_stmt,
            "si",
            $email,
            $id
        );

        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);


        if (mysqli_num_rows($check_result) > 0) {

            $error = "This email address is already registered with another account.";

        } else {

            /* Update profile */

            $stmt = mysqli_prepare(
                $conn,
                "UPDATE users
                 SET full_name = ?,
                     email = ?,
                     phone = ?,
                     department = ?,
                     graduation_year = ?,
                     company = ?,
                     job_title = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "sssssssi",
                $full_name,
                $email,
                $phone,
                $department,
                $graduation_year,
                $company,
                $job_title,
                $id
            );


            if (mysqli_stmt_execute($stmt)) {

                /* Update session name */

                $_SESSION['name'] = $full_name;

                $message = "Profile updated successfully!";

            } else {

                $error = "Unable to update profile. Please try again.";

            }

        }

    }

}


/* =========================================
   GET CURRENT PROFILE DATA
========================================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT full_name, email, phone, department,
            graduation_year, company, job_title
     FROM users
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);


if (!$row) {

    echo "Profile not found.";

    exit();

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Edit Profile | Alumni Portal</title>


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


/* Logo */

.logo {

    text-align: center;

    color: white;

    font-size: 23px;

    font-weight: bold;

    margin-bottom: 35px;

    padding-bottom: 20px;

    border-bottom: 1px solid rgba(255,255,255,0.2);

}


/* Sidebar Links */

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


/* Active */

.sidebar a.active {

    background: white;

    color: #0b3d91;

    font-weight: bold;

}


/* Logout */

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
   PAGE HEADER
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
   FORM CONTAINER
========================================= */

.form-container {

    max-width: 850px;

    background: white;

    padding: 35px;

    border-radius: 15px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.08);

}


/* =========================================
   MESSAGE
========================================= */

.success-message {

    background: #dcfce7;

    color: #166534;

    border: 1px solid #86efac;

    padding: 14px;

    border-radius: 8px;

    margin-bottom: 20px;

}


.error-message {

    background: #fee2e2;

    color: #991b1b;

    border: 1px solid #fca5a5;

    padding: 14px;

    border-radius: 8px;

    margin-bottom: 20px;

}


/* =========================================
   FORM GRID
========================================= */

.form-grid {

    display: grid;

    grid-template-columns: repeat(2, 1fr);

    gap: 20px;

}


/* =========================================
   FORM GROUP
========================================= */

.form-group {

    display: flex;

    flex-direction: column;

}


.form-group.full-width {

    grid-column: 1 / -1;

}


.form-group label {

    color: #334155;

    font-size: 14px;

    font-weight: bold;

    margin-bottom: 8px;

}


.form-group input {

    width: 100%;

    padding: 13px 14px;

    border: 1px solid #cbd5e1;

    border-radius: 7px;

    font-size: 15px;

    outline: none;

    transition: 0.3s;

}


.form-group input:focus {

    border-color: #0b3d91;

    box-shadow: 0 0 0 3px rgba(11,61,145,0.1);

}


/* =========================================
   BUTTONS
========================================= */

.button-container {

    margin-top: 30px;

    display: flex;

    gap: 15px;

}


.save-btn {

    background: #0b3d91;

    color: white;

    border: none;

    padding: 13px 25px;

    border-radius: 7px;

    font-size: 15px;

    font-weight: bold;

    cursor: pointer;

    transition: 0.3s;

}


.save-btn:hover {

    background: #1456c4;

    transform: translateY(-2px);

}


.cancel-btn {

    background: #e2e8f0;

    color: #334155;

    text-decoration: none;

    padding: 13px 25px;

    border-radius: 7px;

    font-size: 15px;

    font-weight: bold;

    transition: 0.3s;

}


.cancel-btn:hover {

    background: #cbd5e1;

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


    .form-grid {

        grid-template-columns: 1fr;

    }


    .form-group.full-width {

        grid-column: auto;

    }


    .button-container {

        flex-direction: column;

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


    <a href="view_students.php">

        🎓 View Students

    </a>


    <a href="../logout.php" class="logout">

        🚪 Logout

    </a>


</div>


<div class="main-content">


    <!-- PAGE HEADER -->

    <div class="page-header">

        <h1>

            Edit My Profile ✏️

        </h1>


        <p>

            Update your personal and professional information.

        </p>

    </div>



    <!-- FORM -->

    <div class="form-container">


        <?php if (!empty($message)): ?>

            <div class="success-message">

                ✅

                <?php

                echo htmlspecialchars($message);

                ?>

            </div>

        <?php endif; ?>



        <?php if (!empty($error)): ?>

            <div class="error-message">

                ❌

                <?php

                echo htmlspecialchars($error);

                ?>

            </div>

        <?php endif; ?>



        <form method="POST"
              action="">


            <div class="form-grid">


                <!-- FULL NAME -->

                <div class="form-group">

                    <label>

                        Full Name

                    </label>


                    <input

                        type="text"

                        name="full_name"

                        value="<?php

                        echo htmlspecialchars(
                            $row['full_name'] ?? ''
                        );

                        ?>"

                        required

                    >

                </div>



                <!-- EMAIL -->

                <div class="form-group">

                    <label>

                        Email Address

                    </label>


                    <input

                        type="email"

                        name="email"

                        value="<?php

                        echo htmlspecialchars(
                            $row['email'] ?? ''
                        );

                        ?>"

                        required

                    >

                </div>



                <!-- PHONE -->

                <div class="form-group">

                    <label>

                        Phone Number

                    </label>


                    <input

                        type="text"

                        name="phone"

                        value="<?php

                        echo htmlspecialchars(
                            $row['phone'] ?? ''
                        );

                        ?>"

                        placeholder="Enter phone number"

                    >

                </div>



                <!-- DEPARTMENT -->

                <div class="form-group">

                    <label>

                        Department

                    </label>


                    <input

                        type="text"

                        name="department"

                        value="<?php

                        echo htmlspecialchars(
                            $row['department'] ?? ''
                        );

                        ?>"

                        placeholder="Example: Computer Science"

                    >

                </div>



                <!-- GRADUATION YEAR -->

                <div class="form-group">

                    <label>

                        Graduation Year

                    </label>


                    <input

                        type="number"

                        name="graduation_year"

                        value="<?php

                        echo htmlspecialchars(
                            $row['graduation_year'] ?? ''
                        );

                        ?>"

                        placeholder="Example: 2024"

                    >

                </div>



                <!-- COMPANY -->

                <div class="form-group">

                    <label>

                        Company

                    </label>


                    <input

                        type="text"

                        name="company"

                        value="<?php

                        echo htmlspecialchars(
                            $row['company'] ?? ''
                        );

                        ?>"

                        placeholder="Enter company name"

                    >

                </div>



                <!-- JOB TITLE -->

                <div class="form-group full-width">

                    <label>

                        Job Title

                    </label>


                    <input

                        type="text"

                        name="job_title"

                        value="<?php

                        echo htmlspecialchars(
                            $row['job_title'] ?? ''
                        );

                        ?>"

                        placeholder="Example: Software Developer"

                    >

                </div>


            </div>



            <!-- BUTTONS -->

            <div class="button-container">


                <button

                    type="submit"

                    class="save-btn"

                >

                    💾 Save Changes

                </button>


                <a

                    href="profile.php"

                    class="cancel-btn"

                >

                    Cancel

                </a>


            </div>


        </form>


    </div>


</div>


</body>

</html>