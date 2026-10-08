<?php

session_start();

include "../database/connection.php";

/* =========================================
   ADMIN LOGIN CHECK
========================================= */

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}


/* =========================================
   DELETE MESSAGE
========================================= */

if (isset($_GET['delete_id'])) {

    $delete_id = intval($_GET['delete_id']);

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM messages WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $delete_id
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    header("Location: messages.php?deleted=1");
    exit();
}


/* =========================================
   GET ALL CONTACT MESSAGES
========================================= */

$query = "SELECT id, name, email, subject, message, sent_at
          FROM messages
          WHERE name IS NOT NULL
          ORDER BY sent_at DESC";

$result = mysqli_query($conn, $query);


/* =========================================
   TOTAL MESSAGES
========================================= */

$count_query = "SELECT COUNT(*) AS total
                FROM messages
                WHERE name IS NOT NULL";

$count_result = mysqli_query($conn, $count_query);

$count_data = mysqli_fetch_assoc($count_result);

$total_messages = $count_data['total'];

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Contact Messages | Admin</title>


<style>

/* =========================================
   RESET
========================================= */

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;

}


body {

    font-family: Arial, Helvetica, sans-serif;

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


.sidebar h2 {

    color: white;

    text-align: center;

    margin-bottom: 30px;

    padding-bottom: 20px;

    border-bottom:
        1px solid
        rgba(255,255,255,0.2);

}


.sidebar a {

    display: block;

    color: white;

    text-decoration: none;

    padding: 14px 18px;

    margin-bottom: 6px;

    border-radius: 8px;

    transition: 0.3s;

}


.sidebar a:hover {

    background:
        rgba(255,255,255,0.15);

    transform:
        translateX(4px);

}


.sidebar a.active {

    background: white;

    color: #0b3d91;

    font-weight: bold;

}


/* =========================================
   MAIN CONTENT
========================================= */

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

    color: #64748b;

    margin-top: 7px;

}


/* =========================================
   BACK BUTTON
========================================= */

.back-btn {

    background: #0b3d91;

    color: white;

    text-decoration: none;

    padding: 12px 20px;

    border-radius: 8px;

    font-weight: bold;

    transition: 0.3s;

}


.back-btn:hover {

    background: #1456c4;

    transform: translateY(-2px);

}


/* =========================================
   MESSAGE STAT CARD
========================================= */

.message-summary {

    display: flex;

    align-items: center;

    gap: 20px;

    background: white;

    padding: 22px 25px;

    border-radius: 12px;

    box-shadow:
        0 5px 20px
        rgba(0,0,0,0.07);

    margin-bottom: 25px;

    max-width: 300px;

}


.message-icon {

    width: 55px;

    height: 55px;

    background: #e8f0ff;

    color: #0b3d91;

    border-radius: 12px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 25px;

}


.message-summary h3 {

    color: #64748b;

    font-size: 14px;

    margin-bottom: 5px;

}


.message-summary strong {

    color: #0b3d91;

    font-size: 28px;

}


/* =========================================
   MESSAGES CONTAINER
========================================= */

.messages-container {

    display: grid;

    grid-template-columns:
        repeat(
            auto-fit,
            minmax(320px, 1fr)
        );

    gap: 25px;

}


/* =========================================
   MESSAGE CARD
========================================= */

.message-card {

    background: white;

    border-radius: 14px;

    padding: 25px;

    box-shadow:
        0 5px 20px
        rgba(0,0,0,0.07);

    transition: 0.3s;

    border-left:
        4px solid
        #0b3d91;

}


.message-card:hover {

    transform:
        translateY(-5px);

    box-shadow:
        0 10px 30px
        rgba(0,0,0,0.12);

}


/* =========================================
   MESSAGE HEADER
========================================= */

.message-header {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    margin-bottom: 18px;

}


.sender-info {

    display: flex;

    gap: 12px;

    align-items: center;

}


.sender-avatar {

    width: 45px;

    height: 45px;

    border-radius: 50%;

    background: #0b3d91;

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-weight: bold;

    font-size: 18px;

}


.sender-info h3 {

    color: #1e293b;

    font-size: 17px;

}


.sender-info p {

    color: #64748b;

    font-size: 13px;

    margin-top: 4px;

}


.message-id {

    background: #eef2ff;

    color: #0b3d91;

    padding: 5px 9px;

    border-radius: 5px;

    font-size: 12px;

    font-weight: bold;

}


/* =========================================
   SUBJECT
========================================= */

.subject {

    color: #0b3d91;

    font-size: 18px;

    font-weight: bold;

    margin-bottom: 12px;

}


/* =========================================
   MESSAGE
========================================= */

.message-text {

    color: #475569;

    line-height: 1.6;

    font-size: 14px;

    background: #f8fafc;

    padding: 15px;

    border-radius: 8px;

    margin-bottom: 18px;

    word-wrap: break-word;

}


/* =========================================
   MESSAGE FOOTER
========================================= */

.message-footer {

    display: flex;

    justify-content: space-between;

    align-items: center;

    border-top:
        1px solid
        #e2e8f0;

    padding-top: 15px;

}


.date {

    color: #64748b;

    font-size: 12px;

}


.delete-btn {

    background: #fee2e2;

    color: #b91c1c;

    text-decoration: none;

    padding: 8px 12px;

    border-radius: 6px;

    font-size: 13px;

    font-weight: bold;

    transition: 0.3s;

}


.delete-btn:hover {

    background: #b91c1c;

    color: white;

}


/* =========================================
   NO MESSAGES
========================================= */

.no-messages {

    background: white;

    padding: 60px 30px;

    text-align: center;

    border-radius: 14px;

    box-shadow:
        0 5px 20px
        rgba(0,0,0,0.07);

}


.no-messages-icon {

    font-size: 55px;

    margin-bottom: 15px;

}


.no-messages h2 {

    color: #334155;

    margin-bottom: 8px;

}


.no-messages p {

    color: #64748b;

}


/* =========================================
   SUCCESS MESSAGE
========================================= */

.success-message {

    background: #dcfce7;

    color: #166534;

    padding: 13px 18px;

    border-radius: 8px;

    margin-bottom: 20px;

}


/* =========================================
   RESPONSIVE
========================================= */

@media (max-width: 800px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;

    }


    .main-content {

        margin-left: 0;

        padding: 25px 20px;

    }


    .page-header {

        flex-direction: column;

        align-items: flex-start;

        gap: 20px;

    }


    .messages-container {

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


    <a href="manage_announcements.php">

        📢 Manage Announcements

    </a>


    <a href="messages.php"
       class="active">

        📩 Contact Messages

    </a>


    <a href="../qr_scanner.php">

        📷 QR Scanner

    </a>


    <a href="../logout.php">

        🚪 Logout

    </a>

</div>


<!-- =========================================
     MAIN CONTENT
========================================= -->

<div class="main-content">


    <!-- PAGE HEADER -->

    <div class="page-header">


        <div>

            <h1>
                📩 Contact Messages
            </h1>

            <p>
                Manage messages received from Alumni Portal visitors.
            </p>

        </div>


        <a
            href="dashboard.php"
            class="back-btn"
        >

            ← Dashboard

        </a>


    </div>


    <?php if (isset($_GET['deleted'])): ?>

        <div class="success-message">

            ✅ Message deleted successfully.

        </div>

    <?php endif; ?>


    <!-- MESSAGE SUMMARY -->

    <div class="message-summary">


        <div class="message-icon">

            📩

        </div>


        <div>

            <h3>
                Total Messages
            </h3>


            <strong>

                <?php
                echo $total_messages;
                ?>

            </strong>

        </div>


    </div>


    <!-- =====================================
         MESSAGES
    ====================================== -->


    <?php

    if (
        $result &&
        mysqli_num_rows($result) > 0
    ):

    ?>


    <div class="messages-container">


        <?php

        while (
            $row =
            mysqli_fetch_assoc($result)
        ):

            $initial =
                strtoupper(
                    substr(
                        $row['name'],
                        0,
                        1
                    )
                );

        ?>


        <!-- MESSAGE CARD -->

        <div class="message-card">


            <!-- HEADER -->

            <div class="message-header">


                <div class="sender-info">


                    <div class="sender-avatar">

                        <?php
                        echo htmlspecialchars(
                            $initial
                        );
                        ?>

                    </div>


                    <div>

                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $row['name']
                            );
                            ?>

                        </h3>


                        <p>

                            <?php
                            echo htmlspecialchars(
                                $row['email']
                            );
                            ?>

                        </p>

                    </div>


                </div>


                <span class="message-id">

                    #<?php
                    echo $row['id'];
                    ?>

                </span>


            </div>


            <!-- SUBJECT -->

            <div class="subject">

                <?php
                echo htmlspecialchars(
                    $row['subject']
                );
                ?>

            </div>


            <!-- MESSAGE -->

            <div class="message-text">

                <?php

                echo nl2br(
                    htmlspecialchars(
                        $row['message']
                    )
                );

                ?>

            </div>


            <!-- FOOTER -->

            <div class="message-footer">


                <span class="date">

                    🕐

                    <?php
                    echo htmlspecialchars(
                        $row['sent_at']
                    );
                    ?>

                </span>


                <a
                    href="messages.php?delete_id=<?php echo $row['id']; ?>"
                    class="delete-btn"
                    onclick="
                        return confirm(
                            'Are you sure you want to delete this message?'
                        );
                    "
                >

                    🗑️ Delete

                </a>


            </div>


        </div>


        <?php

        endwhile;

        ?>


    </div>


    <?php

    else:

    ?>


    <!-- NO MESSAGES -->

    <div class="no-messages">


        <div class="no-messages-icon">

            📭

        </div>


        <h2>

            No Messages Yet

        </h2>


        <p>

            Contact messages submitted through the website
            will appear here.

        </p>


    </div>


    <?php

    endif;

    ?>


</div>


</body>

</html>