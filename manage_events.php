<?php
session_start();
include("../database/connection.php");

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'admin'
) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $delete_query = "DELETE FROM events WHERE id = $id";

    mysqli_query($conn, $delete_query);

    header("Location: manage_events.php");
    exit();
}


$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$search_safe = mysqli_real_escape_string($conn, $search);

if (!empty($search_safe)) {

    $sql = "SELECT * FROM events
            WHERE event_name LIKE '%$search_safe%'
            OR venue LIKE '%$search_safe%'
            OR description LIKE '%$search_safe%'
            ORDER BY event_date ASC";

} else {

    $sql = "SELECT * FROM events
            ORDER BY event_date ASC";

}

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Events - Admin Panel</title>


<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}
body {
    background: #f4f6f9;
    color: #222;
}
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


.sidebar a.active {
    background: #1456c4;

    border-left: 4px solid #ffc107;
}

.main-content {
    margin-left: 250px;

    padding: 40px;

    min-height: 100vh;
}


.page-header {
    margin-bottom: 30px;
}


.page-header h1 {
    color: #0b3d91;

    font-size: 32px;
}


.page-header p {
    color: #666;

    margin-top: 7px;

    font-size: 16px;
}


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

    min-width: 850px;
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
.event-name {
    font-weight: bold;

    color: #0b3d91;
}
.event-date {
    color: #198754;

    font-weight: bold;

    white-space: nowrap;
}

.venue {
    font-weight: 600;

    color: #444;
}

.description {
    max-width: 350px;

    line-height: 1.5;

    color: #555;
}

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

.no-events {
    text-align: center;

    padding: 40px;

    color: #777;

    font-size: 17px;
}

@media (max-width: 800px) {

    .sidebar {
        width: 200px;
    }

    .main-content {
        margin-left: 200px;

        padding: 25px;
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

<div class="sidebar">

    <h2>Admin Panel</h2>

    <a href="dashboard.php">
        🏠 Dashboard
    </a>

    <a href="manage_users.php">
        👥 Manage Users
    </a>

    <a href="manage_jobs.php">
        💼 Manage Jobs
    </a>

    <a href="manage_events.php" class="active">
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

<div class="main-content">


    <div class="page-header">

        <h1>📅 Manage Events</h1>

        <p>
            View, search and manage all events posted on the Alumni Portal.
        </p>

    </div>

    <div class="search-box">

        <form
            method="GET"
            action="manage_events.php"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                placeholder="Search by event name, venue or description..."
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
                    href="manage_events.php"
                    class="clear-btn"
                >
                    Clear
                </a>

            <?php } ?>

        </form>

    </div>

    <div class="table-card">

        <div class="table-container">

            <table>

                <tr>

                    <th>ID</th>

                    <th>Event Name</th>

                    <th>Event Date</th>

                    <th>Venue</th>

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


                    <td class="event-name">

                        <?php
                        echo htmlspecialchars($row['event_name']);
                        ?>

                    </td>


                    <td class="event-date">

                        📅
                        <?php
                        echo htmlspecialchars($row['event_date']);
                        ?>

                    </td>


                    <td class="venue">

                        📍
                        <?php
                        echo htmlspecialchars($row['venue']);
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
                            href="manage_events.php?delete=<?php echo $row['id']; ?>"
                            class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this event?');"
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
                        colspan="6"
                        class="no-events"
                    >

                        <?php

                        if (!empty($search)) {

                            echo "No events found matching your search.";

                        } else {

                            echo "No events found.";

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