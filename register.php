<?php

include 'database/connection.php';

$error = "";

if (isset($_POST['register'])) {
    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $plain_password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? '';
    $graduation_year = $_POST['graduation_year'] ?? '';
    $department = $_POST['department'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $job_title = trim($_POST['job_title'] ?? '');
    if (
        empty($name) ||
        empty($email) ||
        empty($plain_password) ||
        empty($confirm_password) ||
        empty($role) ||
        empty($graduation_year) ||
        empty($department) ||
        empty($phone)
    ) {

        $error = "Please fill in all required fields.";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }

    elseif (strlen($plain_password) < 6) {

        $error = "Password must contain at least 6 characters.";

    }

    elseif ($plain_password !== $confirm_password) {

        $error = "Passwords do not match.";

    }

    elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $error = "Please enter a valid 10-digit phone number.";

    }

    elseif ($role !== 'student' && $role !== 'alumni') {

        $error = "Please select a valid role.";

    }
 else {

        $check_sql = "SELECT id FROM users WHERE email = ?";

        $check_stmt = mysqli_prepare($conn, $check_sql);

        mysqli_stmt_bind_param(
            $check_stmt,
            "s",
            $email
        );

        mysqli_stmt_execute($check_stmt);

        mysqli_stmt_store_result($check_stmt);


        if (mysqli_stmt_num_rows($check_stmt) > 0) {

            $error = "This email is already registered. Please use another email.";

        }

        mysqli_stmt_close($check_stmt);
    }

    if (empty($error)) {


        $password = password_hash(
            $plain_password,
            PASSWORD_DEFAULT
        );


        $sql = "INSERT INTO users
        (
            full_name,
            email,
            password,
            role,
            graduation_year,
            department,
            phone,
            company,
            job_title
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";


        $stmt = mysqli_prepare($conn, $sql);


        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "sssssssss",
                $name,
                $email,
                $password,
                $role,
                $graduation_year,
                $department,
                $phone,
                $company,
                $job_title
            );


            if (mysqli_stmt_execute($stmt)) {

                echo "<script>
                    alert('Registration Successful! You can now login.');
                    window.location='login.php';
                </script>";

                exit();

            } else {

                $error = "Registration failed. Please try again.";

            }


            mysqli_stmt_close($stmt);

        } else {

            $error = "Something went wrong. Please try again.";

        }

    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Alumni Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --navy:#0B1F3A;
            --navy-deep:#071527;
            --gold:#C9A227;
            --gold-soft:#E4C766;
            --paper:#FEFAE0;
            --ink:#1B1B18;
            --line:rgba(255,255,255,0.14);
        }

        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Inter',Arial,sans-serif; }

        body{
            min-height:100vh;
            display:flex;
            background:var(--paper);
            color:var(--ink);
        }

        .side{
            position:relative;
            flex:1;
            background:var(--navy);
            color:#fff;
            display:flex;
            flex-direction:column;
            justify-content:center;
            padding:60px;
            overflow:hidden;
        }

        .side::before{
            content:'';
            position:absolute; inset:0;
            background-image:
                linear-gradient(var(--line) 1px, transparent 1px),
                linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size:64px 64px;
            opacity:.3;
            mask-image:radial-gradient(ellipse 70% 60% at 30% 40%, black 30%, transparent 75%);
        }

        #network{ position:absolute; inset:0; width:100%; height:100%; }

        .side-content{ position:relative; z-index:2; max-width:360px; }

        .brand{
            display:flex; align-items:center; gap:10px;
            font-family:'Fraunces',serif; font-weight:700; font-size:20px;
            margin-bottom:44px;
        }

        .brand .ring{
            width:28px; height:28px; border-radius:50%;
            border:2px solid var(--gold); color:var(--gold);
            display:flex; align-items:center; justify-content:center;
            font-size:12px; font-weight:700;
        }

        .side h1{
            font-family:'Fraunces',serif; font-size:30px; line-height:1.2; font-weight:600;
            opacity:0; animation:rise .8s .1s ease forwards;
        }

        .side h1 em{ font-style:italic; color:var(--gold-soft); font-weight:400; }

        .side p{
            margin-top:14px; font-size:14.5px; line-height:1.6; color:#C4CCDB;
            opacity:0; animation:rise .8s .25s ease forwards;
        }

        .side ul{
            margin-top:30px; list-style:none;
            opacity:0; animation:rise .8s .4s ease forwards;
        }

        .side ul li{
            font-size:13.5px; color:#C4CCDB; margin-bottom:11px;
            padding-left:20px; position:relative;
        }

        .side ul li::before{
            content:'—'; position:absolute; left:0; color:var(--gold-soft);
        }

        @keyframes rise{ from{opacity:0; transform:translateY(14px);} to{opacity:1; transform:translateY(0);} }

        .form-side{
            flex:1.2;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:40px 40px;
            overflow-y:auto;
        }

        .form-card{
            width:100%;
            max-width:440px;
            opacity:0;
            animation:rise .8s .15s ease forwards;
            padding:20px 0;
        }

        .form-card .kicker{
            font-size:12px; letter-spacing:.14em; text-transform:uppercase;
            color:var(--gold); font-weight:700;
        }

        .form-card h2{
            font-family:'Fraunces',serif; font-size:26px; margin-top:8px; margin-bottom:26px; color:var(--navy);
        }

        .field{ margin-bottom:17px; }
        .field-row{ display:flex; gap:14px; }
        .field-row .field{ flex:1; }

        .field label{
            display:block; font-size:12.5px; font-weight:600; color:var(--navy);
            margin-bottom:6px; letter-spacing:.02em;
        }

        .field input,
        .field select{
            width:100%; padding:12px 13px;
            border:1px solid rgba(11,31,58,.18);
            border-radius:2px; font-size:14px;
            background:#fff;
            font-family:'Inter',sans-serif;
            transition:border-color .2s ease, box-shadow .2s ease;
        }

        .field input:focus,
        .field select:focus{
            outline:none;
            border-color:var(--gold);
            box-shadow:0 0 0 3px rgba(201,162,39,.15);
        }

        .field-hint{
            font-size:11.5px; color:#8A8A80; margin-top:5px;
        }

        .btn-primary{
            width:100%;
            background:var(--navy);
            color:#fff;
            border:none;
            padding:14px;
            font-size:15px;
            font-weight:700;
            border-radius:2px;
            cursor:pointer;
            margin-top:8px;
            transition:background .25s ease, transform .25s ease, box-shadow .25s ease;
        }

        .btn-primary:hover{
            background:var(--gold);
            color:var(--navy-deep);
            transform:translateY(-2px);
            box-shadow:0 12px 24px rgba(201,162,39,.28);
        }

        .switch-line{
            text-align:center; margin-top:22px; font-size:13.5px; color:#5B5B54;
        }

        .switch-line a{ color:var(--navy); font-weight:700; text-decoration:none; }
        .switch-line a:hover{ color:var(--gold); }

        .back-line{ text-align:center; margin-top:10px; }
        .back-line a{ color:#8A8A80; font-size:13px; text-decoration:none; }
        .back-line a:hover{ color:var(--navy); }

        .error-msg{
            background:#FDEDED; color:#9A2A2A; border:1px solid #F3CACA;
            padding:11px 14px; font-size:13px; border-radius:2px; margin-bottom:18px;
        }

        @media(max-width:900px){
            body{ flex-direction:column; }
            .side{ padding:36px; min-height:200px; }
            .side h1{ font-size:22px; }
            .side ul{ display:none; }
            .field-row{ flex-direction:column; gap:0; }
        }
    </style>
</head>
<body>

<div class="side">
    <canvas id="network"></canvas>
    <div class="side-content">
        <div class="brand"><span class="ring">A</span>Alumni Portal</div>
        <h1>Join <em>your</em><br>batch network.</h1>
        <p>Create your profile once — stay connected for every reunion after.</p>
        <ul>
            <li>Get listed in the alumni directory</li>
            <li>See job posts from your batchmates</li>
            <li>RSVP to reunions & events</li>
        </ul>
    </div>
</div>

<div class="form-side">
    <div class="form-card">
        <div class="kicker">Create Account</div>
        <h2>Alumni Portal Registration</h2>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <div class="field">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="field">
    <label for="confirm_password">Confirm Password</label>
    <input
        type="password"
        id="confirm_password"
        name="confirm_password"
        placeholder="Re-enter your password"
        required
    >
</div>

            <div class="field-row">
                <div class="field">
                    <label for="role">Select Role</label>
                    <select id="role" name="role" required>
                        <option value="">-- Select Role --</option>
                        <option value="student">Student</option>
                        <option value="alumni">Alumni</option>
                    </select>
                </div>
                <div class="field">
                    <label for="graduation_year">Graduation Year</label>
                    <input type="number" id="graduation_year" name="graduation_year" min="2000" max="2100" required>
                </div>
            </div>

            <div class="field">
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="">-- Select Department --</option>
                    <option value="Computer Science Engineering">Computer Science Engineering</option>
                    <option value="Information Technology">Information Technology</option>
                    <option value="Electronics and Communication">Electronics and Communication</option>
                    <option value="Electrical Engineering">Electrical Engineering</option>
                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                    <option value="Civil Engineering">Civil Engineering</option>
                </select>
            </div>

            <div class="field">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" maxlength="10" required>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="company">Current Company</label>
                    <input type="text" id="company" name="company">
                    <div class="field-hint">Alumni only</div>
                </div>
                <div class="field">
                    <label for="job_title">Current Job Title</label>
                    <input type="text" id="job_title" name="job_title">
                    <div class="field-hint">Alumni only</div>
                </div>
            </div>

            <button type="submit" name="register" class="btn-primary">Register</button>
        </form>

        <div class="switch-line">
            Already have an account? <a href="login.php">Login</a>
        </div>
        <div class="back-line">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>
</div>

<script>
const canvas = document.getElementById('network');
const ctx = canvas.getContext('2d');
let w, h, nodes = [];

function resize(){ w = canvas.width = canvas.offsetWidth; h = canvas.height = canvas.offsetHeight; }
function initNodes(){
    nodes = Array.from({length:20}, () => ({
        x: Math.random()*w, y: Math.random()*h,
        vx: (Math.random()-.5)*0.2, vy: (Math.random()-.5)*0.2
    }));
}
function draw(){
    ctx.clearRect(0,0,w,h);
    nodes.forEach(n => {
        n.x += n.vx; n.y += n.vy;
        if(n.x<0||n.x>w) n.vx*=-1;
        if(n.y<0||n.y>h) n.vy*=-1;
    });
    for(let i=0;i<nodes.length;i++){
        for(let j=i+1;j<nodes.length;j++){
            const a=nodes[i], b=nodes[j];
            const d=Math.hypot(a.x-b.x,a.y-b.y);
            if(d<140){
                ctx.strokeStyle = `rgba(201,162,39,${0.12*(1-d/140)})`;
                ctx.lineWidth = 1;
                ctx.beginPath(); ctx.moveTo(a.x,a.y); ctx.lineTo(b.x,b.y); ctx.stroke();
            }
        }
    }
    nodes.forEach(n => {
        ctx.fillStyle = 'rgba(228,199,102,0.55)';
        ctx.beginPath(); ctx.arc(n.x,n.y,1.6,0,Math.PI*2); ctx.fill();
    });
    requestAnimationFrame(draw);
}
window.addEventListener('resize', ()=>{resize(); initNodes();});
resize(); initNodes(); draw();
</script>

</body>
</html>