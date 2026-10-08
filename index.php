<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Portal</title>

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
            --line:rgba(255, 255, 255, 0.11);
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Inter',Arial,sans-serif;
        }

        body{
            background:var(--paper);
            color:var(--ink);
            overflow-x:hidden;
        }

        h1, .brand{ font-family:'Fraunces',serif; }

        /* NAV */
        nav{
            position:sticky;
            top:0;
            z-index:50;
            background:var(--navy);
            color:white;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:16px 40px;
            border-bottom:1px solid var(--line);
        }

        nav h2.brand{
            font-size:20px;
            font-weight:700;
            display:flex;
            align-items:center;
            gap:10px;
        }

        nav h2.brand::before{
            content:'A';
            width:28px;
            height:28px;
            border-radius:50%;
            border:2px solid var(--gold);
            color:var(--gold);
            font-family:'Fraunces',serif;
            font-weight:700;
            font-size:12px;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        nav ul{
            list-style:none;
            display:flex;
        }

        nav ul li{
            margin-left:28px;
        }

        nav ul li a{
            color:#D9DFEA;
            text-decoration:none;
            font-weight:600;
            font-size:14.5px;
            position:relative;
            padding:4px 0;
            transition:color .25s ease;
        }

        nav ul li a::after{
            content:'';
            position:absolute;
            left:0;
            bottom:-2px;
            width:0;
            height:1.5px;
            background:var(--gold);
            transition:width .3s ease;
        }

        nav ul li a:hover{ color:#fff; }
        nav ul li a:hover::after{ width:100%; }

        /* HERO */
        .hero{
            position:relative;
            background:var(--navy);
            color:#fff;
            text-align:center;
            padding:110px 20px 130px;
            overflow:hidden;
        }

        .hero::before{
            content:'';
            position:absolute;
            inset:0;
            background-image:
                linear-gradient(var(--line) 1px, transparent 1px),
                linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size:64px 64px;
            opacity:.35;
            mask-image:radial-gradient(ellipse 70% 60% at 50% 30%, black 30%, transparent 75%);
        }

        #network{
            position:absolute;
            inset:0;
            width:100%;
            height:100%;
        }

        .hero-inner{
            position:relative;
            z-index:2;
        }

        .eyebrow{
            display:inline-flex;
            align-items:center;
            gap:8px;
            font-size:12.5px;
            letter-spacing:.14em;
            text-transform:uppercase;
            color:var(--gold-soft);
            font-weight:600;
            margin-bottom:22px;
            opacity:0;
            animation:rise .8s .1s ease forwards;
        }

        .hero h1{
            font-size:clamp(34px, 5.4vw, 56px);
            font-weight:700;
            line-height:1.1;
            letter-spacing:-.5px;
            opacity:0;
            animation:rise .9s .25s ease forwards;
        }

        .hero p{
            margin-top:20px;
            font-size:18px;
            line-height:1.6;
            color:#C4CCDB;
            opacity:0;
            animation:rise .9s .42s ease forwards;
        }

        .btn{
            display:inline-block;
            margin-top:34px;
            padding:14px 30px;
            background:var(--gold);
            color:var(--navy-deep);
            font-weight:700;
            text-decoration:none;
            border-radius:2px;
            opacity:0;
            animation:rise .9s .58s ease forwards;
            transition:transform .25s ease, box-shadow .25s ease;
        }

        .btn:hover{
            transform:translateY(-3px);
            box-shadow:0 12px 28px rgba(201,162,39,.35);
        }

        @keyframes rise{
            from{ opacity:0; transform:translateY(18px); }
            to{ opacity:1; transform:translateY(0); }
        }

        /* STATS */
        .stats{
            background:var(--navy-deep);
            color:#fff;
            display:grid;
            grid-template-columns:repeat(4,1fr);
            border-top:1px solid var(--line);
            border-bottom:1px solid var(--line);
        }

        .stat{
            text-align:center;
            padding:32px 12px;
            border-right:1px solid var(--line);
        }

        .stat:last-child{ border-right:none; }

        .stat .num{
            font-family:'Fraunces',serif;
            font-size:32px;
            font-weight:600;
            color:var(--gold-soft);
        }

        .stat .label{
            font-size:12px;
            letter-spacing:.08em;
            text-transform:uppercase;
            color:#9FAAC0;
            margin-top:6px;
        }

        #about{
            padding:90px 40px;
            max-width:1100px;
            margin:0 auto;
        }

        #about .kicker{
            font-size:12.5px;
            letter-spacing:.14em;
            text-transform:uppercase;
            color:var(--navy);
            font-weight:700;
        }

        #about h2{
            font-family:'Fraunces',serif;
            font-size:clamp(26px,3vw,34px);
            margin-top:10px;
            margin-bottom:44px;
            color:var(--navy);
        }

        .grid{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:26px;
        }

        .card{
            background:#fff;
            border:1px solid rgba(11,31,58,.08);
            padding:30px 26px;
            border-radius:3px;
            opacity:0;
            transform:translateY(24px);
            transition:opacity .7s ease, transform .7s ease, box-shadow .3s ease, border-color .3s ease;
        }

        .card.in{ opacity:1; transform:translateY(0); }

        .card:hover{
            box-shadow:0 20px 40px rgba(11,31,58,.08);
            border-color:var(--gold);
        }

        .card .idx{
            font-family:'Fraunces',serif;
            font-size:13px;
            color:var(--gold);
            font-weight:600;
            letter-spacing:.06em;
        }

        .card h3{
            font-size:19px;
            margin:12px 0 8px;
            font-weight:600;
            color:var(--navy);
        }

        .card p{
            font-size:14.5px;
            line-height:1.6;
            color:#5B5B54;
        }

        /* FOOTER */
        footer{
            background:var(--navy);
            color:#C4CCDB;
            text-align:center;
            padding:22px;
            margin-top:0;
            font-size:13.5px;
        }

        @media(max-width:820px){
            nav ul{ display:none; }
            .grid{ grid-template-columns:1fr; }
            .stats{ grid-template-columns:repeat(2,1fr); }
        }
    </style>

</head>
<body>

<nav>
    <h2 class="brand">Alumni Portal</h2>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="index.php#about">About</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="contact.php">Contact</a></li>
       <li><a href="thankyou.php" class="thank-btn"> View Project</a></li>
    </ul>
</nav>

<div class="hero">
    <canvas id="network"></canvas>
    <div class="hero-inner">
        <div class="eyebrow">CSE Batch Network</div>
        <h1>Welcome to Alumni Portal</h1>
        <p>Connect Students and Alumni Together</p>
        <a href="/alumni_portal_new/register.php" class="btn">Get Started</a>
    </div>
</div>

<div class="stats">
    <div class="stat"><div class="num" data-target="500">0</div><div class="label">Alumni</div></div>
    <div class="stat"><div class="num" data-target="50">0</div><div class="label">Companies</div></div>
    <div class="stat"><div class="num" data-target="12">0</div><div class="label">Batches</div></div>
    <div class="stat"><div class="num" data-target="120">0</div><div class="label">Events</div></div>
</div>

<div id="about">
    <div class="kicker">What you get</div>
    <h2>A portal built around the batch, not the login screen.</h2>
    <div class="grid">
        <div class="card">
            <div class="idx">Directory</div>
            <h3>Find Alumni</h3>
            <p>Search by batch, branch, or company to reconnect with people you graduated alongside.</p>
        </div>
        <div class="card">
            <div class="idx">Careers</div>
            <h3>Job Board</h3>
            <p>Alumni post openings at their companies first — before they ever reach a public listing.</p>
        </div>
        <div class="card">
            <div class="idx">Gatherings</div>
            <h3>Events & Reunions</h3>
            <p>RSVP to reunions, guest lectures, and meetups organized by your own batch reps.</p>
        </div>
    </div>
</div>

<footer>
    <p>© 2026 Alumni Portal | CSE Mini Project</p>
</footer>

<script>
// Scroll-reveal for feature cards
const cards = document.querySelectorAll('.card');
const io = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('in');
            io.unobserve(e.target);
        }
    });
}, { threshold: .2 });
cards.forEach(c => io.observe(c));

// Animated stat counters
const nums = document.querySelectorAll('.stat .num');
const counterIO = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            const el = e.target, target = +el.dataset.target;
            let cur = 0;
            const step = Math.max(1, Math.round(target / 40));
            const tick = () => {
                cur += step;
                if (cur >= target) { el.textContent = target + '+'; }
                else { el.textContent = cur; requestAnimationFrame(tick); }
            };
            tick();
            counterIO.unobserve(el);
        }
    });
}, { threshold: .5 });
nums.forEach(n => counterIO.observe(n));

// Subtle animated network in hero background
const canvas = document.getElementById('network');
const ctx = canvas.getContext('2d');
let w, h, nodes = [];

function resize() {
    w = canvas.width = canvas.offsetWidth;
    h = canvas.height = canvas.offsetHeight;
}

function initNodes() {
    nodes = Array.from({ length: 30 }, () => ({
        x: Math.random() * w,
        y: Math.random() * h,
        vx: (Math.random() - .5) * 0.25,
        vy: (Math.random() - .5) * 0.25
    }));
}

function draw() {
    ctx.clearRect(0, 0, w, h);
    nodes.forEach(n => {
        n.x += n.vx; n.y += n.vy;
        if (n.x < 0 || n.x > w) n.vx *= -1;
        if (n.y < 0 || n.y > h) n.vy *= -1;
    });
    for (let i = 0; i < nodes.length; i++) {
        for (let j = i + 1; j < nodes.length; j++) {
            const a = nodes[i], b = nodes[j];
            const d = Math.hypot(a.x - b.x, a.y - b.y);
            if (d < 150) {
                ctx.strokeStyle = `rgba(201,162,39,${0.12 * (1 - d / 150)})`;
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(a.x, a.y);
                ctx.lineTo(b.x, b.y);
                ctx.stroke();
            }
        }
    }
    nodes.forEach(n => {
        ctx.fillStyle = 'rgba(228,199,102,0.55)';
        ctx.beginPath();
        ctx.arc(n.x, n.y, 1.6, 0, Math.PI * 2);
        ctx.fill();
    });
    requestAnimationFrame(draw);
}

window.addEventListener('resize', () => { resize(); initNodes(); });
resize();
initNodes();
draw();
</script>

</body>
</html>