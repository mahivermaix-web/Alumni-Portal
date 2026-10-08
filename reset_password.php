<?php
session_start();

$error = "";
$success = "";

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include("database/connection.php");

    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($new_password) || empty($confirm_password)) {

        $error = "Please fill in both password fields.";

    } elseif ($new_password !== $confirm_password) {

        $error = "Passwords do not match.";

    } elseif (strlen($new_password) < 6) {

        $error = "Password must be at least 6 characters.";

    } else {

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users SET password = ? WHERE email = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $hashed_password,
            $email
        );

        if (mysqli_stmt_execute($stmt)) {

            unset($_SESSION['reset_email']);

            $success = "Password reset successfully!";

        } else {

            $error = "Something went wrong. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password - Alumni Portal</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

      body {
    font-family: Arial, sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #FEFAE0;
    color: #1B1B18;
}

        .reset-box {
    width: 400px;
    background: #ffffff;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 12px 35px rgba(11, 31, 58, 0.18);
}

       h2 {
    text-align: center;
    color: #0B1F3A;
    font-family: Georgia, serif;
    font-size: 28px;
    margin-bottom: 12px;
}

        .description {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #0B1F3A;
        }

        input[type="password"] {
            width: 100%;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
            margin-bottom: 18px;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #0B1F3A;
        }

        .btn {
            width: 100%;
            padding: 13px;
            background: #0B1F3A;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background: #C9A227;
            color: #0B1F3A;
        }

        .error {
            background: #fdeaea;
            color: #a00000;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .success {
            background: #e8f7ed;
            color: #18743a;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #0B1F3A;
            text-decoration: none;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="reset-box">

    <h2>Reset Password</h2>

    <p class="description">
        Create a new password for your Alumni Portal account.
    </p>

    <?php if (!empty($error)): ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>


    <?php if (!empty($success)): ?>

        <div class="success">
            <?php echo htmlspecialchars($success); ?>
            <br><br>

            <a href="login.php">
                Go to Login
            </a>
        </div>

    <?php else: ?>

        <form method="POST">

            <label>New Password</label>

            <input
                type="password"
                name="new_password"
                placeholder="Enter new password"
                required
            >

            <label>Confirm Password</label>

            <input
                type="password"
                name="confirm_password"
                placeholder="Confirm new password"
                required
            >

            <button type="submit" class="btn">
                Reset Password
            </button>

        </form>

    <?php endif; ?>


    <a href="login.php" class="back">
        ← Back to Login
    </a>

</div>

</body>

</html>