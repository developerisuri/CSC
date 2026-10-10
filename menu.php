<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CSC Menu</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }

  body, html {
    min-height: 100%;
    width: 100%;
    background: linear-gradient(120deg, #001F3F, #003366, #004C99);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    color: #fff;
    overflow-x: hidden;
    position: relative;
    cursor: none;
  }
  @keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  /* Navigation */
  nav {
    position: sticky;
    top:0;
    width:100%;
    background: rgba(0,31,63,0.85);
    display:flex;
    justify-content: space-between;
    align-items:center;
    padding: 15px 50px;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 15px rgba(0, 230, 230, 0.3);
    z-index: 10;
  }
  nav .logo { width: 50px; height: auto; filter: drop-shadow(0 0 8px #00ffff); animation: logoNavGlow 2s infinite alternate; }
  @keyframes logoNavGlow { from { filter: drop-shadow(0 0 5px #00b3b3); } to { filter: drop-shadow(0 0 20px #00ffff); } }
  nav ul { list-style:none; display:flex; gap:30px; }
  nav ul li a { text-decoration:none; color:#00e6e6; font-weight:500; transition:0.3s; }
  nav ul li a:hover { color:#00FFFF; text-shadow: 0 0 8px #00e6e6; }

  /* Particles */
  .glow {
    position: absolute;
    width: 15px;
    height: 15px;
    background: rgba(0, 255, 255, 0.3);
    border-radius: 50%;
    box-shadow: 0 0 15px rgba(0,255,255,0.7);
    animation: float 12s linear infinite;
    pointer-events: none;
  }
  @keyframes float {
    0% { transform: translateY(0) translateX(0) scale(1); opacity:0.3; }
    50% { transform: translateY(-100vh) translateX(50px) scale(1.5); opacity:0.1; }
    100% { transform: translateY(0) translateX(0) scale(1); opacity:0.3; }
  }

  /* Main container */
  main {
    display: flex;
    flex-direction: column;
    justify-content:center;
    align-items:center;
    padding: 80px 20px 50px;
    position: relative;
    z-index:2;
  }
  main h1 {
    font-size:2.5rem;
    color:yellow;
    margin-bottom:50px;
    text-shadow: 0 0 10px #00B3B3, 0 0 20px #00FFFF;
    animation: textGlow 2s infinite alternate;
  }
  @keyframes textGlow { from { text-shadow: 0 0 5px #00B3B3, 0 0 10px #00FFFF; } to { text-shadow: 0 0 20px #00FFFF, 0 0 40px #00B3B3; } }

  /* Cards */
  .menu-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); gap:50px; width:100%; max-width:900px; }
  .menu-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding:50px 20px;
    text-align:center;
    cursor:pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 0 20px rgba(0,255,255,0.2);
    animation: slideInUp 1s ease forwards;
  }
  .menu-card:hover { transform: scale(1.05); box-shadow: 0 0 40px #f3f73aff, 0 0 60px #00FFFF; }
  .menu-card i { font-size:3rem; margin-bottom:20px; color:#00B3B3; animation: iconGlow 2s infinite alternate; }
  @keyframes iconGlow { from { text-shadow: 0 0 10px #00B3B3; } to { text-shadow: 0 0 30px #00FFFF; } }
  .menu-card h2 { font-size:1.5rem; margin-bottom:10px; }
  .menu-card p { font-size:1rem; color:#00e6e6; }
  @keyframes slideInUp { 0% { transform: translateY(100px); opacity:0; } 100% { transform: translateY(0); opacity:1; } }

  /* Footer */
  footer {
    background: rgba(0, 31, 63, 0.85);
    backdrop-filter: blur(10px);
    text-align: center;
    padding: 20px 50px;
    color: #00e6e6;
    font-size: 0.9em;
    box-shadow: 0 -2px 15px rgba(0, 230, 230, 0.2);
  }
  footer a { color: #00ffff; text-decoration:none; margin:0 10px; transition:0.3s;}
  footer a:hover { text-shadow:0 0 5px #00ffff; }

  @media(max-width:800px){
    main h1 { font-size:2rem; margin-bottom:30px; }
    .menu-grid { gap:30px; }
    .menu-card { padding:40px 15px; }
  }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

<nav>
  <img src="logo.png" alt="CSC Logo" class="logo">
  <ul>
    <li><a href="menu.php">Menu</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<!-- Interactive Particles -->
<div class="glow" style="left:10%; top:20%;"></div>
<div class="glow" style="left:30%; top:50%;"></div>
<div class="glow" style="left:50%; top:30%;"></div>
<div class="glow" style="left:70%; top:60%;"></div>
<div class="glow" style="left:90%; top:40%;"></div>

<main>
  <h1>CSC Customer Menu</h1>
  <div class="menu-grid">
    <div class="menu-card" onclick="window.location.href='enter.php'">
      <i class="fas fa-user-plus"></i>
      <h2>Enter Customer Details</h2>
      <p>Add new customer information into the system</p>
    </div>
    <div class="menu-card" onclick="window.location.href='view.php'">
      <i class="fas fa-users"></i>
      <h2>View Customer Details</h2>
      <p>Check all customer information and records</p>
    </div>
  </div>
</main>

<footer>
  &copy; 2025 CSC Shipping System | 
  <a href="#">Facebook</a> | 
  <a href="#">Twitter</a> | 
  <a href="#">LinkedIn</a>
</footer>

<script>
  // Interactive particle effect
  const particles = document.querySelectorAll('.glow');
  document.addEventListener('mousemove', e => {
    const mouseX = e.clientX;
    const mouseY = e.clientY;
    particles.forEach((p, i) => {
      const speed = (i + 1) * 0.03;
      const offsetX = (mouseX - window.innerWidth/2) * speed;
      const offsetY = (mouseY - window.innerHeight/2) * speed;
      p.style.transform = `translate(${offsetX}px, ${offsetY}px)`;
    });
  });
</script>

</body>
</html>
