<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $delete_query = "DELETE FROM announcements WHERE id = $id";

    mysqli_query($conn, $delete_query);

    header("Location: manage_announcements.php");
    exit();
}

$sql = "SELECT * FROM announcements ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Announcements | Admin Portal</title>


<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Arial, sans-serif;
}

body {
    background: #f4f7fb;
    color: #1f2937;
}

.sidebar {

    position: fixed;

    top: 0;
    left: 0;

    width: 250px;

    height: 100vh;

    background: linear-gradient(
        180deg,
        #06143d,
        #0b3d91
    );

    padding-top: 25px;

    box-shadow: 4px 0 15px rgba(0,0,0,0.08);

}


.sidebar h2 {

    color: white;

    text-align: center;

    font-size: 24px;

    margin-bottom: 35px;

}


.sidebar a {

    display: flex;

    align-items: center;

    gap: 10px;

    color: #ffffff;

    text-decoration: none;

    padding: 15px 25px;

    font-size: 15px;

    transition: 0.3s;

}


.sidebar a:hover {

    background: rgba(255,255,255,0.12);

    padding-left: 30px;

}
.sidebar a.active {

    background: #ffc107;

    color: #06143d;

    font-weight: bold;

    border-left: 5px solid white;

}

.main-content {

    margin-left: 250px;

    padding: 35px;

    min-height: 100vh;

}

.page-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 30px;

}


.page-header h1 {

    color: #06143d;

    font-size: 30px;

}


.page-header p {

    color: #6b7280;

    margin-top: 5px;

}
.back-btn {

    background: #06143d;

    color: white;

    padding: 11px 18px;

    text-decoration: none;

    border-radius: 7px;

    font-weight: 600;

    transition: 0.3s;

}


.back-btn:hover {

    background: #0b3d91;

    transform: translateY(-2px);

}

.content-card {

    background: white;

    border-radius: 14px;

    padding: 25px;

    box-shadow: 0 8px 25px rgba(0,0,0,0.08);

}

.card-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 20px;

}


.card-header h2 {

    color: #06143d;

    font-size: 21px;

}


.total-count {

    background: #e8f0ff;

    color: #0b3d91;

    padding: 8px 14px;

    border-radius: 20px;

    font-size: 14px;

    font-weight: bold;

}

.table-container {

    overflow-x: auto;

}

table {

    width: 100%;

    border-collapse: collapse;

    min-width: 700px;

}


th {

    background: #06143d;

    color: white;

    padding: 15px;

    text-align: left;

    font-size: 14px;

}


td {

    padding: 15px;

    border-bottom: 1px solid #e5e7eb;

    vertical-align: top;

    font-size: 14px;

}


tr:hover {

    background: #f8fafc;

}

.id-badge {

    background: #eef2ff;

    color: #0b3d91;

    padding: 6px 10px;

    border-radius: 6px;

    font-weight: bold;

}

.title {

    font-weight: bold;

    color: #06143d;

    max-width: 220px;

}
.message {

    max-width: 400px;

    line-height: 1.6;

    color: #4b5563;

}

.date {

    color: #6b7280;

    white-space: nowrap;

}

.delete-btn {

    display: inline-block;

    background: #dc3545;

    color: white;

    padding: 8px 14px;

    text-decoration: none;

    border-radius: 6px;

    font-size: 13px;

    font-weight: 600;

    transition: 0.3s;

}


.delete-btn:hover {

    background: #b02a37;

    transform: translateY(-2px);

}
.no-data {

    text-align: center;

    padding: 50px;

    color: #6b7280;

    font-size: 16px;

}

@media (max-width: 800px) {

    .sidebar {

        width: 200px;

    }


    .main-content {

        margin-left: 200px;

        padding: 20px;

    }


    .page-header {

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

    }


    .sidebar h2 {

        margin-bottom: 15px;

    }


    .main-content {

        margin-left: 0;

        padding: 15px;

    }

}

</style>

</head>


<body>

<div class="sidebar">

    <h2>⚙️ Admin Panel</h2>


    <a href="dashboard.php">
        🏠 Dashboard
    </a>


    <a href="manage_users.php">
        👥 Manage Users
    </a>


    <a href="manage_jobs.php">
        💼 Manage Jobs
    </a>


    <a href="manage_events.php">
        📅 Manage Events
    </a>


    <a href="manage_announcements.php" class="active">
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

<div class="main-content">

    <div class="page-header">

        <div>

            <h1>📢 Manage Announcements</h1>

            <p>
                View and manage all announcements posted on the Alumni Portal.
            </p>

        </div>


        <a href="dashboard.php" class="back-btn">
            ← Dashboard
        </a>

    </div>

    <div class="content-card">


        <div class="card-header">

            <h2>
                All Announcements
            </h2>


            <span class="total-count">

                <?php

                if ($result) {

                    echo mysqli_num_rows($result);

                } else {

                    echo "0";

                }

                ?>

                Announcements

            </span>

        </div>
  
      <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Title</th>

                        <th>Message</th>

                        <th>Created At</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if ($result && mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                ?>


                    <tr>


                        <td>

                            <span class="id-badge">

                                #<?php

                                echo htmlspecialchars(
                                    $row['id']
                                );

                                ?>

                            </span>

                        </td>



                        <td class="title">

                            <?php

                            echo htmlspecialchars(
                                $row['title']
                            );

                            ?>

                        </td>



                        <td class="message">

                            <?php

                            echo nl2br(
                                htmlspecialchars(
                                    $row['message']
                                )
                            );

                            ?>

                        </td>



                        <td class="date">

                            <?php

                            echo htmlspecialchars(
                                $row['created_at']
                            );

                            ?>

                        </td>



                        <td>

                            <a

                                href="manage_announcements.php?delete=<?php echo $row['id']; ?>"

                                class="delete-btn"

                                onclick="return confirm('Are you sure you want to delete this announcement?');"

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

                        <td colspan="5" class="no-data">

                            📢 No announcements found.

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