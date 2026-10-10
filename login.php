<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Prepare and execute SQL query
        $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $password_hash);
            $stmt->fetch();

            if (password_verify($password, $password_hash)) {
                // Successful login
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $success = "Login successful! Redirecting...";
                // Optional: redirect after 1 second
                header("Refresh:1; url=menu.php");
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Username not found.";
        }
        $stmt->close();
    }

    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CSC Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }

  body, html {
    min-height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    background: linear-gradient(120deg, #001F3F, #003366, #004C99);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    color: #fff;
    overflow-x: hidden;
    position: relative;
  }

  @keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  /* === Navigation === */
  nav {
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
  nav .logo {
    width: 50px;
    height: auto;
    filter: drop-shadow(0 0 8px #00ffff);
    animation: logoNavGlow 2s infinite alternate;
  }
  @keyframes logoNavGlow {
    from { filter: drop-shadow(0 0 5px #00b3b3); }
    to { filter: drop-shadow(0 0 20px #00ffff); }
  }
  nav ul {
    list-style: none;
    display: flex;
    gap: 30px;
  }
  nav ul li a {
    text-decoration: none;
    color: #00e6e6;
    font-weight: 500;
    transition: 0.3s;
  }
  nav ul li a:hover {
    color: #00ffff;
    text-shadow: 0 0 10px #00e6e6;
  }

  /* === Floating Glow Particles === */
  .glow {
    position: absolute;
    width: 15px;
    height: 15px;
    background: rgba(0, 255, 255, 0.3);
    border-radius: 50%;
    box-shadow: 0 0 15px rgba(0,255,255,0.7);
    animation: float 12s linear infinite;
  }
  @keyframes float {
    0% { transform: translateY(0) translateX(0) scale(1); opacity:0.3; }
    50% { transform: translateY(-100vh) translateX(50px) scale(1.5); opacity:0.1; }
    100% { transform: translateY(0) translateX(0) scale(1); opacity:0.3; }
  }

  /* === Main Login Container === */
  main {
    flex:1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 80px 20px 50px;
    z-index: 2;
    position: relative;
  }

  .login-container {
    display: flex;
    width: 900px;
    max-width: 95%;
    height: 500px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 0 50px rgba(0,255,255,0.5);
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(25px);
  }

  /* === Left Welcome Panel === */
  .login-left {
    flex:1;
    background: linear-gradient(135deg, #00B3B3, #004C99);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color:#fff;
    font-size:2rem;
    font-weight:600;
    text-align:center;
    padding: 20px;
    animation: slideInLeft 1s ease forwards;
  }
  .login-left img.logo {
    width: 120px;
    margin-bottom: 25px;
    filter: drop-shadow(0 0 15px #00FFFF);
    animation: logoPulse 2s infinite alternate;
  }
  @keyframes logoPulse {
    from { filter: drop-shadow(0 0 15px #00B3B3); }
    to { filter: drop-shadow(0 0 35px #00FFFF); }
  }
  @keyframes slideInLeft {
    0% { transform: translateX(-100%); opacity:0; }
    100% { transform: translateX(0); opacity:1; }
  }

  /* === Right Login Form Panel === */
  .login-right {
    flex:1;
    background: #0a0f2c;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 40px 30px;
    animation: slideInRight 1s ease forwards;
  }
  @keyframes slideInRight {
    0% { transform: translateX(100%); opacity:0; }
    100% { transform: translateX(0); opacity:1; }
  }
  .login-right h1 {
    margin-bottom: 30px;
    color:#00B3B3;
    font-size:2.2rem;
    text-align:center;
    text-shadow: 0 0 10px #00B3B3, 0 0 20px #00FFFF;
    animation: textGlow 2s infinite alternate;
  }
  @keyframes textGlow {
    from { text-shadow: 0 0 5px #00B3B3, 0 0 10px #00FFFF; }
    to { text-shadow: 0 0 20px #00FFFF, 0 0 40px #00B3B3; }
  }
  .login-right form {
    display: flex;
    flex-direction: column;
  }
  .login-right input {
    margin-bottom: 20px;
    padding: 15px 20px;
    border-radius: 30px;
    border: none;
    outline: none;
    background: rgba(0,0,0,0.3);
    color: #fff;
    font-size: 1rem;
    transition: all 0.3s ease;
  }
  .login-right input:focus {
    background: rgba(0,0,0,0.5);
    box-shadow: 0 0 15px #00B3B3, 0 0 25px #00FFFF;
  }
  .login-right button {
    padding: 15px 0;
    border-radius: 30px;
    border: none;
    background: #00B3B3;
    font-weight: 600;
    color: #0a0f2c;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .login-right button:hover {
    background: #00FFFF;
    transform: scale(1.05);
    box-shadow: 0 0 25px #00B3B3, 0 0 40px #00FFFF;
  }
  .login-right a {
    margin-top: 15px;
    color:#00B3B3;
    text-decoration:none;
    font-size:0.9rem;
    text-align:center;
  }
  .login-right a:hover {
    color:#00FFFF;
  }

  /* === Footer === */
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

  @media(max-width:800px){
    .login-container { flex-direction: column; height:auto; }
    .login-left, .login-right { width:100%; padding:30px; }
    .login-left { order:2; }
    .login-right { order:1; }
  }
</style>
</head>
<body>

<!-- Navigation -->
<nav>
  <img src="logo.png" alt="CSC Logo" class="logo">
  <ul>
    <li><a href="#">Home</a></li>
    <li><a href="#">About</a></li>
    <li><a href="#">Contact</a></li>
  </ul>
</nav>

<!-- Floating Glow Particles -->
<div class="glow" style="left: 10%; animation-delay: 0s;"></div>
<div class="glow" style="left: 30%; animation-delay: 2s;"></div>
<div class="glow" style="left: 50%; animation-delay: 4s;"></div>
<div class="glow" style="left: 70%; animation-delay: 6s;"></div>
<div class="glow" style="left: 90%; animation-delay: 8s;"></div>

<!-- Main Login Container -->
<main>
  <div class="login-container">
    <div class="login-left">
      <img src="logo.png" alt="CSC Logo" class="logo">
      Welcome Back!<br>
      <span style="font-size:1rem; font-weight:400;">Enter your credentials to continue</span>
    </div>
    <div class="login-right">
      <h1>Login</h1>

      <!-- Display messages -->
      <?php 
      if (!empty($error)) echo '<div class="error">'.$error.'</div>';
      if (!empty($success)) echo '<div class="success">'.$success.'</div>';
      ?>

      <form action="" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>

      <a href="signup.php">Don't have an account? Sign Up</a><br>
      <a href="forgot.php">Forgot Password?</a>
    </div>
  </div>
</main>


<!-- Footer -->
<footer>
  &copy; 2025 CSC Shipping System | 
  <a href="#">Facebook</a> | 
  <a href="#">Twitter</a> | 
  <a href="#">LinkedIn</a>
</footer>

</body>
</html>
