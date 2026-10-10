<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    // Check for empty fields
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // Insert new user with hashed password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (username, password_hash, created_at) VALUES (?, ?, NOW())");
            $insert_stmt->bind_param("ss", $username, $password_hash);

            if ($insert_stmt->execute()) {
                // Set success message (do NOT redirect)
                $success = "Account created successfully. You can now log in.";
            } else {
                $error = "Error creating account. Please try again.";
            }
            $insert_stmt->close();
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
<title>CSC Sign Up</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
/* Use your existing signin CSS here */
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
html, body { width: 100%; height: 100%; overflow-x: hidden; }
body { display: flex; flex-direction: column; justify-content: flex-start; align-items: center; background: linear-gradient(120deg, #001F3F, #003366, #004C99); background-size: 400% 400%; animation: gradientBG 15s ease infinite; color: #fff; position: relative; min-height: 100vh; }
@keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
nav { position: sticky; top: 0; width: 100%; background: rgba(0, 31, 63, 0.85); display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; backdrop-filter: blur(10px); z-index: 10; box-shadow: 0 2px 15px rgba(0, 230, 230, 0.3); }
nav .logo { width: 50px; filter: drop-shadow(0 0 5px #00e6e6); }
nav ul { list-style: none; display: flex; gap: 30px; }
nav ul li a { text-decoration: none; color: #00e6e6; font-weight: 500; transition: 0.3s; }
nav ul li a:hover { color: #00ffff; text-shadow: 0 0 8px #00e6e6; }
.circle { position: absolute; border-radius: 50%; width: 400px; height: 400px; background: rgba(0, 255, 255, 0.1); animation: floatCircle 12s infinite alternate; z-index: 0; pointer-events: none; }
.circle:nth-child(2) { width: 300px; height: 300px; animation-delay: 3s; background: rgba(0, 179, 179, 0.15); top: 10%; left: 60%; }
.circle:nth-child(3) { width: 200px; height: 200px; animation-delay: 6s; background: rgba(0, 255, 255, 0.08); top: 60%; left: 30%; }
@keyframes floatCircle { 0% { transform: translate(0,0) scale(1); } 50% { transform: translate(30px,-30px) scale(1.1); } 100% { transform: translate(0,0) scale(1); } }
.signin-card { position: relative; z-index: 2; background: rgba(0,0,0,0.5); backdrop-filter: blur(15px); border-radius: 25px; padding: 60px 80px; width: 350px; text-align: center; box-shadow: 0 0 30px rgba(0, 255, 255, 0.5); margin-top: 50px; }
.signin-card:hover { transform: translateY(-5px); box-shadow: 0 0 50px rgba(0,255,255,0.7); }
.signin-card .logo { width: 100px; margin-bottom: 25px; filter: drop-shadow(0 0 15px #00FFFF); animation: logoPulse 2s infinite alternate; }
@keyframes logoPulse { from { filter: drop-shadow(0 0 15px #00B3B3); } to { filter: drop-shadow(0 0 30px #00FFFF); } }
h1 { margin-bottom: 30px; color: #00FFFF; text-shadow: 0 0 10px #00B3B3; font-size: 2rem; }
.signin-card input { width: 100%; padding: 15px 20px; margin: 12px 0; border-radius: 50px; border: 2px solid rgba(0, 255, 255, 0.4); background: rgba(0,0,0,0.3); color: #fff; font-size: 1rem; outline: none; transition: 0.3s; }
.signin-card input:focus { border-color: #00FFFF; box-shadow: 0 0 10px #00FFFF; background: rgba(0,0,0,0.5); }
.signin-card button { width: 100%; padding: 15px 0; margin-top: 20px; border-radius: 50px; border: none; background: linear-gradient(135deg, #00B3B3, #00FFFF); color: #001F3F; font-weight: 600; font-size: 1rem; cursor: pointer; box-shadow: 0 0 15px rgba(0, 255, 255, 0.5); transition: all 0.35s ease; }
.signin-card button:hover { transform: translateY(-5px) scale(1.05); box-shadow: 0 0 30px rgba(0, 255, 255, 0.7), 0 0 40px rgba(0, 179, 179, 0.3); background: linear-gradient(135deg, #00FFFF, #00B3B3); }
.signin-card a { display: inline-block; margin-top: 15px; color: #00FFFF; text-decoration: none; transition: 0.3s; }
.signin-card a:hover { text-shadow: 0 0 5px #00FFFF; }
.error { color: #FF6347; margin-bottom: 15px; font-weight: 600; }
.success { color: #32CD32; margin-bottom: 15px; font-weight: 600; }
footer { width: 100%; background: rgba(0, 31, 63, 0.85); backdrop-filter: blur(10px); text-align: center; padding: 20px 50px; color: #00e6e6; font-size: 0.9em; box-shadow: 0 -2px 15px rgba(0, 230, 230, 0.2); margin-top: auto; }
footer a { color: #00ffff; text-decoration: none; margin: 0 10px; transition: 0.3s; }
footer a:hover { text-shadow: 0 0 5px #00ffff; }
</style>
</head>
<body>

  <nav>
    <img src="logo.png" alt="CSC Logo" class="logo">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
  </nav>

  <div class="circle"></div>
  <div class="circle"></div>
  <div class="circle"></div>

 <div class="signin-card">
    <img src="logo.png" alt="CSC Logo" class="logo">
    <h1>Sign Up</h1>

    <?php 
      if(!empty($error)) { echo '<div class="error">'.$error.'</div>'; } 
    ?>

    <form action="" method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit">Sign Up</button>
    </form>

    <a href="login.php">Already have an account? Login</a>
</div>

<footer>
    &copy; 2025 CSC Shipping System | 
    <a href="#">Facebook</a> | 
    <a href="#">Twitter</a> | 
    <a href="#">LinkedIn</a>
</footer>
</body>
</html>
