<?php
session_start();

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include("database/connection.php");

    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {

        $email = mysqli_real_escape_string($conn, $email);

        $query = "SELECT id, full_name FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {

            $user = mysqli_fetch_assoc($result);
            $_SESSION['reset_email'] = $email;

            header("Location: reset_password.php");
            exit();

        } else {
            $error = "No account found with this email address.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Alumni Portal</title>

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

      .forgot-box {
    width: 400px;
    background: #ffffff;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 12px 35px rgba(11, 31, 58, 0.18);
}

        h2 {
            text-align: center;
            color: #0B1F3A;
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

        input[type="email"] {
            width: 100%;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }

        input[type="email"]:focus {
            outline: none;
            border-color: #0B1F3A;
        }

        .btn {
            width: 100%;
            margin-top: 20px;
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

<div class="forgot-box">

    <h2>Forgot Password?</h2>

    <p class="description">
        Enter your registered email address to reset your password.
    </p>

    <?php if (!empty($error)): ?>
        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Email Address</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your registered email"
            required
        >

        <button type="submit" class="btn">
            Continue
        </button>

    </form>

    <a href="login.php" class="back">
        ← Back to Login
    </a>

</div>

</body>
</html>