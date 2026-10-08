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

if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) != 'alumni') {
    header("Location: ../login.php");
    exit();
}

$alumni_id = $_SESSION['id'];
$sql = "SELECT 
            ja.id AS application_id,
            ja.cover_message,
            ja.status,
            ja.applied_at,
            j.job_title,
            j.company,
            j.location,
            u.full_name AS student_name,
            u.email AS student_email
        FROM job_applications ja
        INNER JOIN jobs j ON ja.job_id = j.id
        INNER JOIN users u ON ja.student_id = u.id
        WHERE j.alumni_id = '$alumni_id'
        ORDER BY ja.applied_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Applications Received | Alumni Portal</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #f4f7fb;
    color: #1f2937;
}

.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: #0b1f3a;
    color: white;
    padding: 25px 18px;
}

.logo {
    text-align: center;
    font-size: 23px;
    font-weight: bold;
    color: #f4c542;
    margin-bottom: 35px;
}

.sidebar a {
    display: block;
    text-decoration: none;
    color: #dbe4f0;
    padding: 13px 15px;
    margin-bottom: 8px;
    border-radius: 8px;
    transition: .3s;
}

.sidebar a:hover,
.sidebar a.active {
    background: #f4c542;
    color: #0b1f3a;
}

.main {
    margin-left: 250px;
    padding: 35px;
}

.header {
    margin-bottom: 30px;
}

.header h1 {
    color: #0b1f3a;
    font-size: 30px;
    margin-bottom: 8px;
}

.header p {
    color: #6b7280;
}

.application-card {
    background: white;
    border-radius: 14px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,.07);
    border-left: 5px solid #f4c542;
}

.top {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.student-name {
    font-size: 21px;
    font-weight: bold;
    color: #0b1f3a;
    margin-bottom: 7px;
}

.email {
    color: #666;
    margin-bottom: 15px;
}

.job {
    font-size: 16px;
    font-weight: bold;
    color: #374151;
}

.details {
    color: #777;
    font-size: 14px;
    margin-top: 7px;
}

.status {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    height: fit-content;
}

.pending {
    background: #fff3cd;
    color: #856404;
}

.accepted {
    background: #d1fae5;
    color: #065f46;
}

.rejected {
    background: #fee2e2;
    color: #991b1b;
}

.message-box {
    margin-top: 20px;
    padding: 18px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
}

.message-box strong {
    display: block;
    color: #0b1f3a;
    margin-bottom: 8px;
}

.message {
    color: #555;
    line-height: 1.6;
    white-space: pre-line;
}

.date {
    margin-top: 15px;
    color: #888;
    font-size: 13px;
}

.application-actions {
    display: flex;
    gap: 12px;
    margin-top: 18px;
}

.accept-btn,
.reject-btn {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    font-size: 14px;
}

.accept-btn {
    background: #198754;
    color: white;
}

.accept-btn:hover {
    background: #146c43;
}

.reject-btn {
    background: #dc3545;
    color: white;
}

.reject-btn:hover {
    background: #b02a37;
}

.empty {
    background: white;
    padding: 55px;
    text-align: center;
    border-radius: 14px;
    box-shadow: 0 5px 20px rgba(0,0,0,.06);
}

.empty-icon {
    font-size: 45px;
    margin-bottom: 15px;
}

.empty h2 {
    color: #0b1f3a;
    margin-bottom: 8px;
}

.empty p {
    color: #777;
}

@media(max-width: 800px) {
    .sidebar {
        width: 200px;
    }

    .main {
        margin-left: 200px;
        padding: 20px;
    }

    .top {
        flex-direction: column;
    }
}
</style>
</head>

<body>

<div class="sidebar">

    <div class="logo">
        Alumni Portal
    </div>

    <a href="dashboard.php">🏠 Dashboard</a>

    <a href="profile.php">👤 My Profile</a>

    <a href="post_job.php">💼 Post Job</a>

    <a href="applications.php" class="active">
        📩 Applications Received
    </a>

    <a href="events.php">📅 Events</a>

    <a href="../logout.php">🚪 Logout</a>

</div>

<div class="main">

    <div class="header">
        <h1>Applications Received</h1>
        <p>View students who have applied for your job opportunities.</p>
    </div>

    <?php if ($result && mysqli_num_rows($result) > 0) { ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <?php
                $status = strtolower($row['status']);

                if ($status == "accepted") {
                    $status_class = "accepted";
                } elseif ($status == "rejected") {
                    $status_class = "rejected";
                } else {
                    $status_class = "pending";
                }
            ?>

            <div class="application-card">

                <div class="top">

                    <div>

                        <div class="student-name">
                            👤 <?php echo htmlspecialchars($row['student_name']); ?>
                        </div>

                        <div class="email">
                            ✉️ <?php echo htmlspecialchars($row['student_email']); ?>
                        </div>

                        <div class="job">
                            💼 <?php echo htmlspecialchars($row['job_title']); ?>
                        </div>

                        <div class="details">
                            🏢 <?php echo htmlspecialchars($row['company']); ?>
                            &nbsp;&nbsp; | &nbsp;&nbsp;
                            📍 <?php echo htmlspecialchars($row['location']); ?>
                        </div>

                    </div>

                    <span class="status <?php echo $status_class; ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>

                </div>

                <div class="message-box">

                    <strong>📝 Cover Message</strong>

                    <div class="message">
                        <?php echo htmlspecialchars($row['cover_message']); ?>
                    </div>

                </div>

                <div class="date">
                    Applied on:
                    <?php echo date("d M Y, h:i A", strtotime($row['applied_at'])); ?>
                </div>

                <?php if ($status == "pending") { ?>

    <div class="application-actions">

        <a href="update_application_status.php?id=<?php echo $row['application_id']; ?>&status=Accepted"
           class="accept-btn">
            ✅ Accept
        </a>

        <a href="update_application_status.php?id=<?php echo $row['application_id']; ?>&status=Rejected"
           class="reject-btn"
           onclick="return confirm('Are you sure you want to reject this application?');">
            ❌ Reject
        </a>

    </div>

<?php } ?>

            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="empty">

            <div class="empty-icon">📭</div>

            <h2>No Applications Yet</h2>

            <p>
                No students have applied for your job opportunities yet.
            </p>

        </div>

    <?php } ?>

</div>

</body>
</html>