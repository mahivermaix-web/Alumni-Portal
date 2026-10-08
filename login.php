<?php
session_start();
$error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : "";
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Portal Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">

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

        body.login-page{
            min-height:100vh;
            display:flex;
            background:var(--paper);
            color:var(--ink);
        }

        .side{
            position:relative;
            flex:1.1;
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

        .side-content{ position:relative; z-index:2; max-width:380px; }

        .brand{
            display:flex; align-items:center; gap:10px;
            font-family:'Fraunces',serif; font-weight:700; font-size:20px;
            margin-bottom:60px;
        }

        .brand .ring{
            width:28px; height:28px; border-radius:50%;
            border:2px solid var(--gold); color:var(--gold);
            display:flex; align-items:center; justify-content:center;
            font-size:12px; font-weight:700;
        }

        .side h1{
            font-family:'Fraunces',serif; font-size:34px; line-height:1.2; font-weight:600;
            opacity:0; animation:rise .8s .1s ease forwards;
        }

        .side h1 em{ font-style:italic; color:var(--gold-soft); font-weight:400; }

        .side p{
            margin-top:16px; font-size:15px; line-height:1.6; color:#C4CCDB;
            opacity:0; animation:rise .8s .25s ease forwards;
        }

        @keyframes rise{ from{opacity:0; transform:translateY(14px);} to{opacity:1; transform:translateY(0);} }

        .form-side{
            flex:1;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:40px;
        }

        .login-box{
            width:100%;
            max-width:380px;
            opacity:0;
            animation:rise .8s .15s ease forwards;
        }

        .login-box h2{
            font-family:'Fraunces',serif; font-size:28px; margin-bottom:30px; color:var(--navy);
        }

        .login-box label{
            display:block; font-size:13px; font-weight:600; color:var(--navy);
            margin-bottom:7px; margin-top:18px; letter-spacing:.02em;
        }

        .login-box input[type="email"],
.login-box input[type="password"] {
    width:100%;
    padding:13px 14px;
    border:1px solid rgba(11,31,58,.18);
    border-radius:2px;
    font-size:14.5px;
    background:#fff;
    font-family:'Inter',sans-serif;
    transition:border-color .2s ease, box-shadow .2s ease;
}
        .login-box input:focus{
            outline:none;
            border-color:var(--gold);
            box-shadow:0 0 0 3px rgba(201,162,39,.15);
        }

        .login-btn {
    width:100%;
    background:var(--navy);
    color:#fff !important;
    border:none;
    padding:14px;
    font-size:15px;
    font-weight:700;
    border-radius:2px;
    cursor:pointer;
    margin-top:26px;
    display:block;
    text-align:center;
    transition:background .25s ease, transform .25s ease, box-shadow .25s ease;
}
.login-btn:hover {
    background:var(--gold);
    color:var(--navy-deep) !important;
    transform:translateY(-2px);
    box-shadow:0 12px 24px rgba(201,162,39,.28);
}

        .register-link{
            text-align:center; margin-top:22px; font-size:14px; color:#5B5B54;
        }

        .register-link a{ color:var(--navy); font-weight:700; text-decoration:none; }
        .register-link a:hover{ color:var(--gold); }

        .error-msg{
            background:#FDEDED; color:#9A2A2A; border:1px solid #F3CACA;
            padding:12px 14px; font-size:13.5px; border-radius:2px; margin-top:20px;
        }

        @media(max-width:860px){
            body.login-page{ flex-direction:column; }
            .side{ padding:40px; min-height:220px; }
            .side h1{ font-size:26px; }
        }
    </style>
</head>

<body class="login-page">

<div class="side">
    <canvas id="network"></canvas>
    <div class="side-content">
        <div class="brand"><span class="ring">A</span>Alumni Portal</div>
        <h1>Welcome back to<br><em>your</em> network.</h1>
        <p>Sign in to reconnect with your batch, browse the job board, and RSVP to upcoming reunions.</p>
    </div>
</div>

<div class="form-side">
    <div class="login-box">

        <h2>Alumni Portal Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">

    <label>Email Address</label>

    <input
        type="email"
        name="email"
        placeholder="Enter your email"
        required
    >


    <label>Password</label>

    <input
        type="password"
        name="password"
        placeholder="Enter your password"
        required
    >


    <div class="forgot-password">
        <a href="forgot_password.php">
            Forgot Password?
        </a>
    </div>


    <input
        type="submit"
        name="login"
        value="Login"
        class="login-btn"
    >

</form>

        <p class="register-link">
            Don't have an account?
            <a href="register.php">Register</a>
        </p>

        <p class="register-link">
            <a href="index.php">← Back to Home</a>
        </p>

    </div>
</div>

<script>
const canvas = document.getElementById('network');
const ctx = canvas.getContext('2d');
let w, h, nodes = [];

function resize(){ w = canvas.width = canvas.offsetWidth; h = canvas.height = canvas.offsetHeight; }
function initNodes(){
    nodes = Array.from({length:22}, () => ({
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