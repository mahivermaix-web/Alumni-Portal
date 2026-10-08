<?php
session_start();

include("database/connection.php");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    /* Validate fields */

    if (
        empty($name) ||
        empty($email) ||
        empty($subject) ||
        empty($message)
    ) {

        $error = "Please fill in all the fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } else {

        /* Insert message securely */

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO messages
            (name, email, subject, message)
            VALUES (?, ?, ?, ?)"
        );

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $name,
                $email,
                $subject,
                $message
            );

            if (mysqli_stmt_execute($stmt)) {

                $success = "Thank you! Your message has been sent successfully.";

            } else {

                $error = "Unable to send your message. Please try again.";

            }

            mysqli_stmt_close($stmt);

        } else {

            $error = "Something went wrong. Please try again later.";

        }

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Contact Us | Alumni Portal</title>


<style>

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

nav {

    width: 100%;

    height: 75px;

    background: #0b3d91;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 7%;

    box-shadow: 0 3px 15px rgba(0,0,0,0.15);

}


.logo {

    color: white;

    font-size: 23px;

    font-weight: bold;

}


nav ul {

    list-style: none;

    display: flex;

    gap: 30px;

}


nav ul li a {

    color: white;

    text-decoration: none;

    font-size: 15px;

    font-weight: 600;

    transition: 0.3s;

}


nav ul li a:hover {

    color: #ffd700;

}


nav ul li a.active {

    color: #ffd700;

}

.contact-section {

    min-height: calc(100vh - 145px);

    padding: 60px 7%;

    display: flex;

    align-items: center;

    justify-content: center;

}


.contact-container {

    width: 100%;

    max-width: 1150px;

    display: grid;

    grid-template-columns: 1fr 1fr;

    background: white;

    border-radius: 18px;

    overflow: hidden;

    box-shadow: 0 15px 40px rgba(0,0,0,0.10);

}



.contact-info {

    background: linear-gradient(
        135deg,
        #0b3d91,
        #082b68
    );

    color: white;

    padding: 50px;

}


.contact-info h1 {

    font-size: 38px;

    margin-bottom: 15px;

}


.contact-info > p {

    color: #dbeafe;

    line-height: 1.7;

    margin-bottom: 35px;

    font-size: 15px;

}


.info-box {

    display: flex;

    gap: 15px;

    margin-bottom: 25px;

    padding: 18px;

    border-radius: 10px;

    background: rgba(255,255,255,0.08);

    border: 1px solid rgba(255,255,255,0.12);

}


.info-icon {

    font-size: 22px;

    min-width: 30px;

}


.info-box h3 {

    margin-bottom: 6px;

    font-size: 16px;

}


.info-box p {

    color: #dbeafe;

    line-height: 1.6;

    font-size: 14px;

}



.contact-form {

    padding: 50px;

}


.contact-form h2 {

    color: #0b3d91;

    font-size: 28px;

    margin-bottom: 8px;

}


.form-subtitle {

    color: #64748b;

    margin-bottom: 25px;

    font-size: 14px;

}


.success-message {

    background: #dcfce7;

    color: #166534;

    border: 1px solid #bbf7d0;

    padding: 13px 15px;

    border-radius: 8px;

    margin-bottom: 20px;

    font-size: 14px;

}


.error-message {

    background: #fee2e2;

    color: #991b1b;

    border: 1px solid #fecaca;

    padding: 13px 15px;

    border-radius: 8px;

    margin-bottom: 20px;

    font-size: 14px;

}




.form-group {

    margin-bottom: 20px;

}


.form-group label {

    display: block;

    font-size: 14px;

    font-weight: bold;

    color: #334155;

    margin-bottom: 8px;

}


.form-group input,

.form-group textarea {

    width: 100%;

    padding: 13px 14px;

    border: 1px solid #cbd5e1;

    border-radius: 8px;

    font-size: 14px;

    outline: none;

    transition: 0.3s;

}


.form-group input:focus,

.form-group textarea:focus {

    border-color: #0b3d91;

    box-shadow: 0 0 0 3px rgba(11,61,145,0.10);

}


.form-group textarea {

    min-height: 130px;

    resize: vertical;

}

.send-btn {

    width: 100%;

    padding: 14px;

    background: #0b3d91;

    color: white;

    border: none;

    border-radius: 8px;

    font-size: 16px;

    font-weight: bold;

    cursor: pointer;

    transition: 0.3s;

}


.send-btn:hover {

    background: #1456c4;

    transform: translateY(-2px);

    box-shadow: 0 8px 20px rgba(11,61,145,0.25);

}


footer {

    height: 70px;

    background: #071f4d;

    color: #cbd5e1;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 14px;

}

@media (max-width: 850px) {

    nav {

        height: auto;

        padding: 20px;

        flex-direction: column;

        gap: 15px;

    }


    nav ul {

        gap: 15px;

        flex-wrap: wrap;

        justify-content: center;

    }


    .contact-section {

        padding: 30px 20px;

    }


    .contact-container {

        grid-template-columns: 1fr;

    }


    .contact-info,

    .contact-form {

        padding: 35px 25px;

    }


}


@media (max-width: 500px) {

    .contact-info h1 {

        font-size: 30px;

    }


    nav ul {

        flex-direction: column;

        align-items: center;

    }

}

</style>

</head>


<body>


<nav>


    <div class="logo">

        🎓 Alumni Portal

    </div>


    <ul>

        <li>
            <a href="index.php">
                Home
            </a>
        </li>


        <li>
            <a href="index.php#about">
                About
            </a>
        </li>


        <li>
            <a href="login.php">
                Login
            </a>
        </li>


        <li>
            <a href="register.php">
                Register
            </a>
        </li>


        <li>
            <a href="contact.php" class="active">
                Contact
            </a>
        </li>

    </ul>

</nav>


<section class="contact-section">


<div class="contact-container">

    <div class="contact-info">


        <h1>

            Let's Connect

        </h1>


        <p>

            Have questions, suggestions, or need assistance?

            Our Alumni Portal team is here to help.

            Send us a message and we will get back to you.

        </p>


        <div class="info-box">

            <div class="info-icon">

                📍

            </div>

            <div>

                <h3>

                    Address

                </h3>

                <p>

                    GH Raisoni College of Engineering and Management<br>

                    CRPF Gate No.3, Digdoh Hills,<br>

                    Hingna Road, Nagpur,<br>

                    Maharashtra - 440016, India

                </p>

            </div>

        </div>


        <div class="info-box">

            <div class="info-icon">

                📧

            </div>

            <div>

                <h3>

                    Email

                </h3>

                <p>

                    alumni@college.com

                </p>

            </div>

        </div>


        <div class="info-box">

            <div class="info-icon">

                📞

            </div>

            <div>

                <h3>

                    Phone

                </h3>

                <p>

                    +91 9876543210

                </p>

            </div>

        </div>


        <div class="info-box">

            <div class="info-icon">

                🕐

            </div>

            <div>

                <h3>

                    Office Hours

                </h3>

                <p>

                    Monday - Friday<br>

                    9:00 AM - 5:00 PM

                </p>

            </div>

        </div>


    </div>

    <div class="contact-form">


        <h2>

            Send Us a Message

        </h2>


        <p class="form-subtitle">

            Fill out the form below and our team will respond to you.

        </p>


        <?php if (!empty($success)): ?>

            <div class="success-message">

                ✅ <?php echo htmlspecialchars($success); ?>

            </div>

        <?php endif; ?>


        <?php if (!empty($error)): ?>

            <div class="error-message">

                ❌ <?php echo htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>


        <form method="POST">


            <div class="form-group">

                <label>

                    Your Name

                </label>


                <input

                    type="text"

                    name="name"

                    placeholder="Enter your full name"

                    required

                >

            </div>


            <div class="form-group">

                <label>

                    Email Address

                </label>


                <input

                    type="email"

                    name="email"

                    placeholder="Enter your email address"

                    required

                >

            </div>


            <div class="form-group">

                <label>

                    Subject

                </label>


                <input

                    type="text"

                    name="subject"

                    placeholder="What is your message about?"

                    required

                >

            </div>


            <div class="form-group">

                <label>

                    Message

                </label>


                <textarea

                    name="message"

                    placeholder="Write your message here..."

                    required

                ></textarea>

            </div>


            <button

                type="submit"

                class="send-btn"

            >

                ✉️ Send Message

            </button>


        </form>


    </div>


</div>


</section>

<footer>

    <p>

        © 2026 Alumni Portal. All Rights Reserved.

    </p>

</footer>


</body>

</html>