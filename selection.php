<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Shipping Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* === Global Styles === */
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow-x: hidden;
    background: linear-gradient(120deg, #001F3F, #003366, #004C99);
    background-size: 400% 400%;
    animation: gradientBG 10s ease infinite;
    position: relative;
    color: #fff;
}

/* === Animated Gradient Background === */
@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* === Navigation Section === */
header nav {
    position: sticky;
    top: 0;
    width: 100%;
    background: rgba(0, 31, 63, 0.85);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 50px;
    backdrop-filter: blur(10px);
    z-index: 10;
    box-shadow: 0 2px 15px rgba(0, 230, 230, 0.3);
}
header nav .logo {
    width: 50px;
    height: auto;
    filter: drop-shadow(0 0 5px #00e6e6);
}
header nav ul {
    list-style: none;
    display: flex;
    gap: 30px;
}
header nav ul li a {
    text-decoration: none;
    color: #00e6e6;
    font-weight: 500;
    transition: 0.3s;
}
header nav ul li a:hover {
    color: #00ffff;
    text-shadow: 0 0 8px #00e6e6;
}

/* === Floating Glow Particles === */
.glow {
    position: absolute;
    width: 10px;
    height: 10px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    animation: float 10s linear infinite;
}
@keyframes float {
    0% { transform: translateY(0) scale(1); opacity: 0.7; }
    50% { transform: translateY(-100vh) scale(1.5); opacity: 0.3; }
    100% { transform: translateY(0) scale(1); opacity: 0.7; }
}

/* === Card Container === */
.card {
    position: relative;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 60px 80px;
    text-align: center;
    box-shadow: 0 0 30px rgba(0, 179, 179, 0.4);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    z-index: 2;
}
.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 0 50px rgba(0, 179, 179, 0.6);
}

/* === Logo inside Card === */
.card .logo {
    width: 120px;
    margin-bottom: 25px;
    filter: drop-shadow(0 0 10px #00B3B3);
    animation: logoPulse 2s infinite alternate;
}
@keyframes logoPulse { from { filter: drop-shadow(0 0 10px #00B3B3); } to { filter: drop-shadow(0 0 25px #00FFFF); } }

h1 { color: #00FFFF; font-size: 2.5rem; margin-bottom: 30px; text-shadow: 0 0 10px #00B3B3; }
p { font-size: 1.1rem; margin-bottom: 40px; color: #ccefff; opacity: 0.8; }

/* === Buttons === */
.btn {
    display: inline-block;
    padding: 15px 45px;
    margin: 10px 15px;
    font-size: 1.05rem;
    font-weight: 500;
    color: #001F3F;
    border-radius: 50px;
    text-decoration: none;
    background: linear-gradient(135deg, #00B3B3, #00FFFF);
    box-shadow: 0 0 15px rgba(0,179,179,0.4);
    transition: all 0.35s ease;
}
.btn:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 0 30px rgba(0, 179, 179, 0.6), 0 0 40px rgba(0, 255, 255, 0.3);
    background: linear-gradient(135deg, #00FFFF, #00B3B3);
}

@media (max-width: 600px) {
    .card { width: 90%; padding: 40px 20px; }
    .btn { width: 80%; margin: 15px 0; display: block; }
}

/* === Footer Section === */
footer {
    background: rgba(0, 31, 63, 0.85);
    backdrop-filter: blur(10px);
    text-align: center;
    padding: 20px 50px;
    color: #00e6e6;
    font-size: 0.9em;
    box-shadow: 0 -2px 15px rgba(0, 230, 230, 0.2);
}
footer a {
    color: #00ffff;
    text-decoration: none;
    margin: 0 10px;
    transition: 0.3s;
}
footer a:hover { text-shadow: 0 0 5px #00ffff; }

/* === Background floating shapes (optional for extra animation) === */
body::before, body::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    width: 400px; height: 400px;
    background: radial-gradient(circle, #FF8C00 0%, transparent 70%);
    animation: float1 12s ease-in-out infinite alternate;
    opacity: 0.6;
}
body::after {
    width: 300px; height: 300px;
    background: radial-gradient(circle, #00B3B3 0%, transparent 70%);
    animation: float2 15s ease-in-out infinite alternate;
    top: 20%; left: 60%;
}
@keyframes float1 { 0% { transform: translate(0,0); } 50% { transform: translate(50px,-50px); } 100% { transform: translate(0,0); } }
@keyframes float2 { 0% { transform: translate(0,0); } 50% { transform: translate(-40px,60px); } 100% { transform: translate(0,0); } }

</style>
</head>
<body>

<!-- === Navigation Section === -->
<header>
    <nav>
        <img src="logo.png" alt="CSC Logo" class="logo">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>
</header>

<!-- === Card Container === -->
<div class="card">
    <img src="logo.png" alt="CSC Logo" class="logo">
    <h1>Smart Shipping Portal</h1>
    <p>Securely manage your shipments with CSC</p>
    <a href="login.php" class="btn">Login</a>
    <a href="signin.php" class="btn">Sign In</a>
</div>

<!-- === Footer Section === -->
<footer>
    &copy; 2025 CSC Shipping System | 
    <a href="#">Facebook</a> | 
    <a href="#">Twitter</a> | 
    <a href="#">LinkedIn</a>
</footer>

</body>
</html>
