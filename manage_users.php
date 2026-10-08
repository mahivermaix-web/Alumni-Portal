<?php
session_start();
include("../database/connection.php");

/* =========================
   ADMIN SECURITY CHECK
========================= */

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}



/* =========================
   DELETE USER
========================= */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    // Prevent admin from deleting their own account
    if ($id != $_SESSION['id']) {

        $delete_query = "DELETE FROM users WHERE id = $id";
        mysqli_query($conn, $delete_query);
    }

    header("Location: manage_users.php");
    exit();
}


/* =========================
   SEARCH USERS
========================= */

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$search_safe = mysqli_real_escape_string($conn, $search);


/* =========================
   GET USERS
========================= */

if (!empty($search_safe)) {

    $sql = "SELECT * FROM users
            WHERE (role = 'student' OR role = 'alumni')
            AND (
                full_name LIKE '%$search_safe%'
                OR email LIKE '%$search_safe%'
                OR department LIKE '%$search_safe%'
            )
            ORDER BY id DESC";

} else {

    $sql = "SELECT * FROM users
            WHERE role = 'student' OR role = 'alumni'
            ORDER BY id DESC";
}


$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Manage Users - Admin Portal</title>


<style>

/* =========================
   RESET
========================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* =========================
   BODY
========================= */

body {
    font-family: Arial, sans-serif;
    background: #f4f7fc;
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

    background: #06143d;

    padding-top: 25px;

    box-shadow: 3px 0 15px rgba(0,0,0,0.1);

}


.sidebar h2 {

    color: white;

    text-align: center;

    margin-bottom: 35px;

    font-size: 25px;

}


.sidebar a {

    display: block;

    color: #dce5ff;

    text-decoration: none;

    padding: 15px 25px;

    font-size: 15px;

    transition: 0.3s;

}


.sidebar a:hover {

    background: #0b3d91;

    color: white;

}


.sidebar a.active {

    background: #0b3d91;

    color: white;

    border-left: 4px solid #ffc107;

}


/* =========================
   MAIN CONTENT
========================= */

.main {

    margin-left: 250px;

    padding: 35px;

    min-height: 100vh;

}


/* =========================
   TOP HEADER
========================= */

.top-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 30px;

}


.top-header h1 {

    color: #06143d;

    font-size: 30px;

}


.admin-name {

    background: white;

    padding: 10px 18px;

    border-radius: 8px;

    box-shadow: 0 3px 12px rgba(0,0,0,0.08);

    color: #0b3d91;

    font-weight: bold;

}


/* =========================
   PAGE INTRO
========================= */

.page-intro {

    margin-bottom: 25px;

}


.page-intro h2 {

    color: #0b3d91;

    margin-bottom: 8px;

}


.page-intro p {

    color: #666;

}


/* =========================
   SEARCH SECTION
========================= */

.search-box {

    background: white;

    padding: 20px;

    border-radius: 12px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.08);

    margin-bottom: 25px;

}


.search-form {

    display: flex;

    gap: 12px;

}


.search-form input {

    flex: 1;

    padding: 13px 15px;

    border: 1px solid #ddd;

    border-radius: 7px;

    font-size: 15px;

}


.search-form input:focus {

    outline: none;

    border-color: #0b3d91;

    box-shadow: 0 0 0 3px rgba(11,61,145,0.1);

}


.search-btn {

    background: #0b3d91;

    color: white;

    border: none;

    padding: 13px 25px;

    border-radius: 7px;

    cursor: pointer;

    font-weight: bold;

}


.search-btn:hover {

    background: #06143d;

}


.clear-btn {

    background: #e9ecef;

    color: #333;

    padding: 13px 20px;

    border-radius: 7px;

    text-decoration: none;

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

    padding: 25px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.08);

    overflow: hidden;

}


/* =========================
   TABLE HEADER
========================= */

.table-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 20px;

}


.table-header h2 {

    color: #06143d;

}


.user-count {

    background: #e8f0ff;

    color: #0b3d91;

    padding: 8px 15px;

    border-radius: 20px;

    font-size: 14px;

    font-weight: bold;

}


/* =========================
   RESPONSIVE TABLE
========================= */

.table-container {

    overflow-x: auto;

}


table {

    width: 100%;

    border-collapse: collapse;

    min-width: 900px;

}


th {

    background: #06143d;

    color: white;

    padding: 15px;

    text-align: left;

    font-size: 14px;

}


td {

    padding: 14px 15px;

    border-bottom: 1px solid #eee;

    font-size: 14px;

}


tr:hover {

    background: #f7f9ff;

}


/* =========================
   ROLE BADGES
========================= */

.role-badge {

    display: inline-block;

    padding: 6px 12px;

    border-radius: 20px;

    font-size: 12px;

    font-weight: bold;

    text-transform: capitalize;

}


.role-student {

    background: #e7f1ff;

    color: #0b3d91;

}


.role-alumni {

    background: #fff3cd;

    color: #856404;

}


/* =========================
   DELETE BUTTON
========================= */

.delete-btn {

    background: #dc3545;

    color: white;

    padding: 8px 14px;

    border-radius: 6px;

    text-decoration: none;

    font-size: 13px;

    font-weight: bold;

    transition: 0.3s;

}


.delete-btn:hover {

    background: #b02a37;

}


/* =========================
   EMPTY MESSAGE
========================= */

.no-users {

    text-align: center;

    padding: 40px;

    color: #777;

    font-size: 16px;

}


/* =========================
   MOBILE RESPONSIVE
========================= */

@media (max-width: 800px) {

    .sidebar {

        width: 200px;

    }

    .main {

        margin-left: 200px;

        padding: 20px;

    }

    .top-header {

        flex-direction: column;

        align-items: flex-start;

        gap: 15px;

    }

}


@media (max-width: 600px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;

        padding-bottom: 10px;

    }

    .sidebar h2 {

        margin-bottom: 10px;

    }

    .main {

        margin-left: 0;

        padding: 15px;

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


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <h2>⚙️ Admin Panel</h2>


    <a href="dashboard.php">
        🏠 Dashboard
    </a>


    <a href="manage_users.php" class="active">
        👥 Manage Users
    </a>


    <a href="manage_jobs.php">
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



    <a href="../logout.php">
        🚪 Logout
    </a>

</div>



<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main">


    <!-- TOP HEADER -->

    <div class="top-header">

        <h1>Manage Users</h1>

        <div class="admin-name">

            👤

            <?php

            echo isset($_SESSION['name'])
                ? htmlspecialchars($_SESSION['name'])
                : "Administrator";

            ?>

        </div>

    </div>



    <!-- PAGE INTRO -->

    <div class="page-intro">

        <h2>User Management</h2>

        <p>
            View and manage registered students and alumni
            in the Alumni Portal.
        </p>

    </div>



    <!-- SEARCH -->

    <div class="search-box">

        <form
            method="GET"
            action="manage_users.php"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                placeholder="🔍 Search by name, email or department..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button
                type="submit"
                class="search-btn"
            >
                Search
            </button>


            <?php if (!empty($search)) { ?>

                <a
                    href="manage_users.php"
                    class="clear-btn"
                >
                    Clear
                </a>

            <?php } ?>

        </form>

    </div>



    <!-- USER TABLE -->

    <div class="table-card">


        <div class="table-header">

            <h2>Registered Users</h2>


            <div class="user-count">

                👥

                <?php

                if ($result) {

                    echo mysqli_num_rows($result);

                } else {

                    echo "0";

                }

                ?>

                Users

            </div>

        </div>



        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Department</th>

                        <th>Graduation Year</th>

                        <th>Phone</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if ($result && mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        $role_class =
                            ($row['role'] == 'student')
                            ? 'role-student'
                            : 'role-alumni';

                ?>


                    <tr>

                        <td>
                            #<?php echo $row['id']; ?>
                        </td>


                        <td>

                            <strong>
                                <?php

                                echo htmlspecialchars(
                                    $row['full_name']
                                );

                                ?>
                            </strong>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['email']
                            );

                            ?>

                        </td>


                        <td>

                            <span
                                class="role-badge
                                <?php echo $role_class; ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $row['role']
                                );

                                ?>

                            </span>

                        </td>


                        <td>

                            <?php

                            echo !empty($row['department'])
                                ? htmlspecialchars($row['department'])
                                : "—";

                            ?>

                        </td>


                        <td>

                            <?php

                            echo !empty($row['graduation_year'])
                                ? htmlspecialchars($row['graduation_year'])
                                : "—";

                            ?>

                        </td>


                        <td>

                            <?php

                            echo !empty($row['phone'])
                                ? htmlspecialchars($row['phone'])
                                : "—";

                            ?>

                        </td>


                        <td>

                            <a
                                class="delete-btn"
                                href="manage_users.php?delete=<?php echo $row['id']; ?>"
                                onclick="return confirm('⚠️ Are you sure you want to delete this user? This action cannot be undone.');"
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
                            colspan="8"
                            class="no-users"
                        >

                            👤 No users found.

                        </td>

                    </tr>


                <?php

                }

                ?>


                </tbody>

            </table>

        </div>

    </div>


</div>


</body>

</html>