<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Alumni Portal - Thank You</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f8fc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

       

        .navbar {
            background: #061b3a;
            color: white;
            height: 78px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 35px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            gap: 35px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: #28a745;
        }


        

        .main {

            flex: 1;

            text-align: center;

            padding: 45px 20px 30px;
        }


        .success-icon {

            width: 80px;
            height: 80px;

            background: #20b45a;

            border-radius: 50%;

            margin: 0 auto 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;

            font-size: 45px;

            font-weight: bold;
        }


       

        .main h1 {

            color: #102544;

            font-size: 48px;

            margin-bottom: 8px;
        }


        .subtitle {

            color: #20a957;

            font-size: 27px;

            font-weight: bold;

            margin-bottom: 25px;
        }


        .divider {

            width: 350px;

            height: 3px;

            background: #b9d0e8;

            margin: 0 auto 25px;

            position: relative;
        }


        .divider span {

            position: absolute;

            left: 50%;

            top: 50%;

            transform: translate(-50%, -50%);

            background: #f4f8fc;

            padding: 0 20px;

            font-size: 25px;
        }

        .description {

            color: #222;

            font-size: 18px;

            line-height: 1.7;

            margin-bottom: 20px;
        }


        .qr-container {

            width: 310px;

            margin: 0 auto;

            background: white;

            padding: 20px;

            border-radius: 14px;

            box-shadow:
                0 5px 20px rgba(0,0,0,0.12);
        }


        #qrcode {

            display: flex;

            justify-content: center;

            align-items: center;
        }


        .github-btn {

            display: inline-block;

            margin-top: 12px;

            background: #071c3b;

            color: white;

            text-decoration: none;

            padding: 13px 28px;

            border-radius: 25px;

            font-size: 17px;

            font-weight: bold;

            transition: 0.3s;
        }


        .github-btn:hover {

            background: #123b70;

            transform: translateY(-2px);
        }


        .message-box {

            max-width: 700px;

            margin: 20px auto 0;

            padding: 20px;

            border: 1px solid #b9d4ee;

            border-radius: 10px;

            background: #f7fbff;
        }


        .message-box p {

            color: #132b4d;

            font-size: 17px;

            margin: 6px 0;
        }


        .thank-you {

            color: #20a957 !important;

            font-size: 20px !important;

            font-weight: bold;
        }

        footer {

            background: #061b3a;

            color: white;

            text-align: center;

            padding: 20px;

            font-size: 15px;
        }


        @media(max-width: 800px) {

            .navbar {

                padding: 0 20px;
            }

            .nav-links {

                display: none;
            }

            .main h1 {

                font-size: 38px;
            }

            .subtitle {

                font-size: 22px;
            }

            .divider {

                width: 250px;
            }
        }

    </style>

</head>


<body>


<nav class="navbar">

    <div class="logo">
        🎓 Alumni Portal
    </div>


    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="index.php#about">About</a>

        <a href="#">Alumni</a>

        <a href="#">Jobs</a>

        <a href="#">Events</a>

        <a href="contact.php">Contact</a>

        <a href="login.php">Login</a>

    </div>

</nav>


<main class="main">


    <div class="success-icon">
        ✓
    </div>


    <h1>
        Thank You!
    </h1>


    <div class="subtitle">
        Project Completed Successfully
    </div>


    <div class="divider">
        <span>🎓</span>
    </div>


    <p class="description">

        Thank you for reviewing my project.<br>

        Scan the QR code below to view the project repository on GitHub.

    </p>

    <div class="qr-container">

        <div id="qrcode"></div>

        <a
            href="YOUR_GITHUB_REPOSITORY_LINK"
            target="_blank"
            class="github-btn"
        >
            ⚫ View on GitHub
        </a>

    </div>


    <div class="message-box">

        <p>
            <strong>
                Alumni Portal
            </strong>
            – Connecting Alumni, Students & Opportunities.
        </p>

        <p class="thank-you">
            Thank You! 💚
        </p>

    </div>


</main>

<footer>

    © 2026 Alumni Portal. All Rights Reserved.

</footer>


<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>


<script>

    const githubURL = "YOUR_GITHUB_REPOSITORY_LINK";

    new QRCode(document.getElementById("qrcode"), {

        text: githubURL,

        width: 260,

        height: 260,

        colorDark: "#000000",

        colorLight: "#ffffff",

        correctLevel: QRCode.CorrectLevel.H

    });

</script>


</body>

</html>