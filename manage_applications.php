<?php
session_start();

include("../database/connection.php");

if (!isset($_SESSION['id']) && isset($_SESSION['user_id'])) {
    $_SESSION['id'] = $_SESSION['user_id'];
}

if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'admin') {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT
            ja.id AS application_id,
            ja.cover_message,
            ja.status,
            ja.applied_at,

            j.job_title,
            j.company,
            j.location,

            student.full_name AS student_name,
            student.email AS student_email,

            alumni.full_name AS alumni_name,
            alumni.email AS alumni_email

        FROM job_applications ja

        INNER JOIN jobs j
            ON ja.job_id = j.id

        INNER JOIN users student
            ON ja.student_id = student.id

        INNER JOIN users alumni
            ON j.alumni_id = alumni.id

        ORDER BY ja.applied_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Applications - Alumni Portal</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #222;
        }

        .header {
            background: #0B1F3A;
            color: white;
            padding: 20px 30px;
        }

        .header h2 {
            margin-bottom: 5px;
        }

       .container {
    width: 95%;
    max-width: 1200px;
    margin: 30px auto;
}
        .back-btn {
            display: inline-block;
            background: #0B1F3A;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: #C9A227;
            color: #0B1F3A;
        }

        .application-card {
    background: white;
    padding: 28px;
    margin-bottom: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    border-left: 5px solid #0B1F3A;
    transition: 0.3s ease;
}

.application-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}
        .application-card h3 {
    color: #0B1F3A;
    margin-bottom: 18px;
    font-size: 21px;
}

       .info {
    margin-bottom: 10px;
    line-height: 1.6;
    color: #444;
    font-size: 15px;
}

        .label {
            font-weight: bold;
            color: #0B1F3A;
        }

       .status {
    display: inline-block;
    padding: 7px 15px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: bold;
    text-transform: capitalize;
    letter-spacing: 0.3px;
}

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .accepted {
            background: #d1e7dd;
            color: #0f5132;
        }

        .rejected {
            background: #f8d7da;
            color: #842029;
        }

       .message {
    margin-top: 20px;
    padding: 18px;
    background: #f8f9fa;
    border-left: 4px solid #C9A227;
    border-radius: 6px;
    line-height: 1.6;
    color: #444;
}

.message strong {
    color: #0B1F3A;
    font-size: 15px;
}

        .empty {
    background: white;
    padding: 50px 30px;
    text-align: center;
    border-radius: 12px;
    box-shadow: 0 5px 18px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
}

.empty h3 {
    color: #0B1F3A;
    font-size: 22px;
    margin-bottom: 10px;
}

.empty p {
    color: #64748b;
    font-size: 15px;
}
@media (max-width: 900px) {

    .container > div:first-of-type {
        grid-template-columns: repeat(2, 1fr) !important;
    }

}

@media (max-width: 600px) {

    .container {
        width: 92%;
    }

    .container > div:first-of-type {
        grid-template-columns: 1fr !important;
    }

    .application-card {
        padding: 20px;
    }

    .application-card h3 {
        font-size: 18px;
    }

}

    </style>

</head>

<body>

<div class="header">

    <h2>Manage Applications</h2>

    <p>Admin Panel - Job Applications</p>

</div>


<div class="container">

    <!-- Application Summary -->

    <div style="
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    ">

        <!-- Total Applications -->
        <div style="
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        ">

            <h3 style="color:#0B1F3A;">
                📋 Total Applications
            </h3>

            <p style="
                font-size:30px;
                font-weight:bold;
                color:#0B1F3A;
                margin-top:10px;
            ">

                <?php

                $total_query = mysqli_query(
                    $conn,
                    "SELECT COUNT(*) AS total FROM job_applications"
                );

                $total = mysqli_fetch_assoc($total_query)['total'];

                echo $total;

                ?>

            </p>

        </div>


        <!-- Pending -->
        <div style="
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        ">

            <h3 style="color:#856404;">
                ⏳ Pending
            </h3>

            <p style="
                font-size:30px;
                font-weight:bold;
                color:#856404;
                margin-top:10px;
            ">

                <?php

                $pending_query = mysqli_query(
                    $conn,
                    "SELECT COUNT(*) AS total
                     FROM job_applications
                     WHERE status='Pending'"
                );

                $pending = mysqli_fetch_assoc($pending_query)['total'];

                echo $pending;

                ?>

            </p>

        </div>


        <!-- Accepted -->
        <div style="
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        ">

            <h3 style="color:#0f5132;">
                ✅ Accepted
            </h3>

            <p style="
                font-size:30px;
                font-weight:bold;
                color:#0f5132;
                margin-top:10px;
            ">

                <?php

                $accepted_query = mysqli_query(
                    $conn,
                    "SELECT COUNT(*) AS total
                     FROM job_applications
                     WHERE status='Accepted'"
                );

                $accepted = mysqli_fetch_assoc($accepted_query)['total'];

                echo $accepted;

                ?>

            </p>

        </div>


        <!-- Rejected -->
        <div style="
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        ">

            <h3 style="color:#842029;">
                ❌ Rejected
            </h3>

            <p style="
                font-size:30px;
                font-weight:bold;
                color:#842029;
                margin-top:10px;
            ">

                <?php

                $rejected_query = mysqli_query(
                    $conn,
                    "SELECT COUNT(*) AS total
                     FROM job_applications
                     WHERE status='Rejected'"
                );

                $rejected = mysqli_fetch_assoc($rejected_query)['total'];

                echo $rejected;

                ?>

            </p>

        </div>

    </div>


    <a href="dashboard.php" class="back-btn">
        ← Back to Dashboard
    </a>

    <div style="
    background:white;
    padding:15px 20px;
    border-radius:10px;
    margin-bottom:25px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
">

    <label style="
        font-weight:bold;
        color:#0B1F3A;
        margin-right:10px;
    ">
        Filter Applications:
    </label>

    <select id="statusFilter"
            onchange="filterApplications()"
            style="
                padding:10px 15px;
                border:1px solid #ccc;
                border-radius:6px;
                font-size:14px;
                cursor:pointer;
            ">

        <option value="all">All Applications</option>
        <option value="pending">Pending</option>
        <option value="accepted">Accepted</option>
        <option value="rejected">Rejected</option>

    </select>

</div>


    <?php if (mysqli_num_rows($result) > 0) { ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <?php
                $status = strtolower($row['status']);
            ?>

         <div class="application-card"
     data-status="<?php echo $status; ?>">
<div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    margin-bottom:18px;
">

    <h3 style="margin-bottom:0;">
        <?php echo htmlspecialchars($row['job_title']); ?>
    </h3>

    <span style="
        background:#eef4ff;
        color:#0B1F3A;
        padding:7px 12px;
        border-radius:20px;
        font-size:13px;
        font-weight:bold;
        white-space:nowrap;
    ">
        Application #<?php echo $row['application_id']; ?>
    </span>

</div>


                <div class="info">
                    <span class="label">Company:</span>
                    <?php echo htmlspecialchars($row['company']); ?>
                </div>


                <div class="info">
                    <span class="label">Location:</span>
                    <?php echo htmlspecialchars($row['location']); ?>
                </div>


                <hr style="margin: 15px 0;">


                <div class="info">
                    <span class="label">Student:</span>
                    <?php echo htmlspecialchars($row['student_name']); ?>
                </div>


                <div class="info">
                    <span class="label">Student Email:</span>
                    <?php echo htmlspecialchars($row['student_email']); ?>
                </div>


                <div class="info">
                    <span class="label">Alumni:</span>
                    <?php echo htmlspecialchars($row['alumni_name']); ?>
                </div>


                <div class="info">
                    <span class="label">Alumni Email:</span>
                    <?php echo htmlspecialchars($row['alumni_email']); ?>
                </div>


                <div class="info">
                    <span class="label">Applied On:</span>
                    <?php echo htmlspecialchars($row['applied_at']); ?>
                </div>


                <div class="info">
                    <span class="label">Status:</span>

                    <span class="status <?php echo $status; ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>

                </div>


                <div class="message">

                    <strong>Cover Message:</strong>

                    <br><br>

                    <?php
                    echo nl2br(htmlspecialchars($row['cover_message']));
                    ?>

                </div>

<div style="margin-top:20px;">

    <a href="application_details.php?id=<?php echo $row['application_id']; ?>"
       style="
            display:inline-block;
            background:#0B1F3A;
            color:white;
            padding:10px 18px;
            border-radius:6px;
            text-decoration:none;
            font-weight:bold;
            font-size:14px;
       ">

        👁️ View Details

    </a>

</div>
            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="empty">

            <h3>No Applications Found</h3>

            <p>
                There are currently no job applications in the system.
            </p>

        </div>

    <?php } ?>

</div>

<script>

function filterApplications() {

    const filter = document.getElementById("statusFilter").value;

    const applications =
        document.querySelectorAll(".application-card");

    applications.forEach(function(application) {

        const status =
            application.getAttribute("data-status");

        if (filter === "all" || status === filter) {

            application.style.display = "block";

        } else {

            application.style.display = "none";

        }

    });

}

</script>
</body>

</html>