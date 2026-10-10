<?php
// enter.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Start session if needed
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "CSC");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize messages
$success = "";
$error = "";

// Run only if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    // Retrieve form values
    $customer_name   = $_POST['customer_name'] ?? '';
    $shipper_name    = $_POST['shipper_name'] ?? '';
    $destination     = $_POST['destination'] ?? '';
    $status          = $_POST['status'] ?? '';
    $previous_status = $_POST['previous_status'] ?? '';  // ✅ New field
    $bl_no           = $_POST['bl_no'] ?? '';
    $email           = $_POST['email'] ?? '';

    // Insert into database (make sure your DB table has a column 'previous_status')
    $stmt = $conn->prepare("INSERT INTO customer_orders (customer_name, shipper_name, destination, status, previous_status, bl_no, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $customer_name, $shipper_name, $destination, $status, $previous_status, $bl_no, $email);

    if ($stmt->execute()) {
        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'cscltracking@gmail.com'; // your Gmail
            $mail->Password   = 'jmtnebndkppffidc';       // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('cscltracking@gmail.com', 'CSC Shipment Tracking');

            // Allow multiple comma-separated email addresses
            $emailList = explode(',', $email);
            foreach ($emailList as $addr) {
                $trimmed = trim($addr);
                if (!empty($trimmed)) {
                    $mail->addAddress($trimmed);
                }
            }

            $mail->isHTML(true);
            $mail->Subject = 'CSC Shipment Tracking Details';
            $mail->Body    = "
                <h3>Dear $customer_name,</h3>
                <p>Your shipment details have been recorded:</p>
                <ul>
                    <li>Shipper: $shipper_name</li>
                    <li>Destination: $destination</li>
                    <li>Previous Status: $previous_status</li>
                    <li>Current Status: $status</li>
                    <li>B/L Number: $bl_no</li>
                </ul>
                <p>Thank you for using CSC Shipping System!</p>
            ";

            $mail->send();
            $success = "✅ Customer details saved and emails sent successfully!";
        } catch (Exception $e) {
            $error = "⚠️ Customer saved but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "❌ Failed to save customer details.";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Enter Customer Details - CSC</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
  * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }

  html, body {
    width:100%;
    min-height:100%;
    background: linear-gradient(120deg, #001F3F, #003366, #004C99, #001F3F);
    background-size: 400% 400%;
    animation: gradientBG 20s ease infinite;
    color:#fff;
    overflow-x:hidden;
  }
  @keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  nav {
    position: sticky;
    top:0;
    width:100%;
    display:flex;
    justify-content: space-between;
    align-items:center;
    padding: 15px 50px;
    backdrop-filter: blur(10px);
    background: rgba(0,31,63,0.85);
    box-shadow: 0 2px 15px rgba(0,230,230,0.3);
  }
  nav .logo { width:50px; filter: drop-shadow(0 0 10px #00FFFF); animation: logoGlow 2s infinite alternate; }
  @keyframes logoGlow { from { filter: drop-shadow(0 0 5px #00B3B3); } to { filter: drop-shadow(0 0 20px #00FFFF); } }
  nav ul { list-style:none; display:flex; gap:30px; }
  nav ul li a { color:#00E6E6; text-decoration:none; font-weight:500; transition:0.3s; }
  nav ul li a:hover { color:#00FFFF; text-shadow:0 0 8px #00E6E6; }

  .glow { position:absolute; width:15px; height:15px; background: rgba(0,255,255,0.3); border-radius:50%; box-shadow: 0 0 15px rgba(0,255,255,0.7); animation: float 12s linear infinite; pointer-events:none; }
  @keyframes float { 0%{transform:translateY(0);opacity:0.3;} 50%{transform:translateY(-100vh);opacity:0.1;} 100%{transform:translateY(0);opacity:0.3;} }

  main { display:flex; flex-direction:column; justify-content:center; align-items:center; padding:80px 20px; position:relative; z-index:2; }

  main h1 {
    font-size:2.8rem;
    font-weight:700;
    text-align:center;
    background: linear-gradient(45deg, #FFD700, #FFFF00, #FFD700);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    animation: neonText 5s ease infinite;
    text-shadow:0 0 10px #FFD700,0 0 20px #FFFF00;
    margin-bottom:50px;
  }
  @keyframes neonText { 0%{background-position:0% 50%;} 50%{background-position:100% 50%;} 100%{background-position:0% 50%;} }

  .form-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(25px);
    border-radius:25px;
    padding:50px 30px;
    max-width:600px;
    width:100%;
    box-shadow:0 0 30px rgba(0,255,255,0.2);
    animation: slideUp 1s ease forwards;
  }
  .form-card form { display:flex; flex-direction:column; }
  .form-card label { font-weight:500; margin-bottom:5px; color:#00E6E6; }
  .form-card input, .form-card select {
    padding:15px 20px;
    margin-bottom:20px;
    border-radius:30px;
    border:none;
    background:rgba(0,0,0,0.3);
    color:#fff;
    font-size:1rem;
    transition: all 0.3s ease;
  }
  .form-card input:focus, .form-card select:focus {
    background:rgba(0,0,0,0.5);
    box-shadow:0 0 15px #00B3B3;
  }
  .form-card button {
    padding:15px 0;
    border-radius:30px;
    border:none;
    background:#00B3B3;
    font-weight:600;
    color:#0a0f2c;
    font-size:1rem;
    cursor:pointer;
    transition:all 0.3s ease;
  }
  .form-card button:hover {
    background:#00FFFF;
    transform:scale(1.05);
    box-shadow:0 0 25px #00B3B3,0 0 40px #00FFFF;
  }

  .alert-success, .alert-error {
    text-align:center;
    padding:12px 20px;
    border-radius:20px;
    font-weight:500;
    margin-bottom:25px;
    width:90%;
    max-width:600px;
    animation: fadeIn 0.6s ease;
  }
  .alert-success { background:rgba(0,255,128,0.15); color:#00FF99; border:1px solid #00FF99; }
  .alert-error { background:rgba(255,0,0,0.15); color:#FF5555; border:1px solid #FF5555; }
  @keyframes fadeIn { from {opacity:0; transform:translateY(-10px);} to {opacity:1; transform:translateY(0);} }

  footer { background: rgba(0,31,63,0.85); text-align:center; padding:20px 50px; color:#00E6E6; font-size:0.9em; box-shadow:0 -2px 15px rgba(0,230,230,0.2); }
  footer a { color:#00FFFF; text-decoration:none; margin:0 10px; }
  footer a:hover { text-shadow:0 0 5px #00FFFF; }

  @keyframes slideUp { 0%{transform:translateY(100px);opacity:0;} 100%{transform:translateY(0);opacity:1;} }
</style>
</head>
<body>

<nav>
  <img src="logo.png" alt="CSC Logo" class="logo">
  <ul>
    <li><a href="menu.php">Menu</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</nav>

<main>
  <h1>Enter Customer Details</h1>

  <?php if($success) echo "<div class='alert-success'>$success</div>"; ?>
  <?php if($error) echo "<div class='alert-error'>$error</div>"; ?>

  <div class="form-card">
    <form action="" method="POST">
      <label for="customer_name">Customer Name</label>
      <input type="text" name="customer_name" id="customer_name" placeholder="Enter customer name" required>

      <label for="shipper_name">Shipper Name</label>
      <input type="text" name="shipper_name" id="shipper_name" placeholder="Enter shipper name" required>

      <label for="destination">Destination</label>
      <input type="text" name="destination" id="destination" placeholder="Enter destination" required>

      <label for="previous_status">Previous Status</label>
       <select name="previous_status" id="previous_status" required>

<option value="">Select status</option>
        <option value="Shipping Document Received">Shipping Document Received</option>
        <option value="Cargo Packing Completed">Cargo Packing Completed</option>
        <option value="Container is stuffed">Container is stuffed</option>
        <option value="Customs Formalities Completed">Customs Formalities Completed</option>
        <option value="In-Gate">In-Gate</option>
        <option value="Loaded on Vessel">Loaded on Vessel</option>

       
        <option value="Vessel Departure">Vessel Departured</option>
        <option value="Documents Dispatched to Shipper">Documents Dispatched to Shipper</option>
        <option value="Vessel Arrival">Vessel Arrival</option>
        <option value="Unloaded from Vessel">Unloaded from Vessel</option>
        <option value="Out-Gate">Out-Gate</option>

        <option value="Customs Cleared">Customs Cleared</option>
        <option value="Delivered">Delivered</option>
        <option value="Shipment on Hold">Shipment on Hold</option>
        <option value="Consignee Notified">Consignee Notified</option>
        

</select>


      <label for="status">Current Status</label>
      <select name="status" id="status" required>
        <option value="">Select status</option>
        <option value="Shipping Document Received">Shipping Document Received</option>
        <option value="Cargo Packing Completed">Cargo Packing Completed</option>
        <option value="Container is stuffed">Container is stuffed</option>
        <option value="Customs Formalities Completed">Customs Formalities Completed</option>
        <option value="In-Gate">In-Gate</option>
        <option value="Loaded on Vessel">Loaded on Vessel</option>

        
        <option value="Vessel Departure">Vessel Departured</option>
        <option value="Documents Dispatched to Shipper">Documents Dispatched to Shipper</option>
        <option value="Vessel Arrival">Vessel Arrival</option>
        <option value="Unloaded from Vessel">Unloaded from Vessel</option>
        <option value="Out-Gate">Out-Gate</option>

        <option value="Customs Cleared">Customs Cleared</option>
        <option value="Delivered">Delivered</option>
        <option value="Shipment on Hold">Shipment on Hold</option>
        <option value="Consignee Notified">Consignee Notified</option>
        
      </select>

      <label for="bl_no">B/L Number</label>
      <input type="text" name="bl_no" id="bl_no" placeholder="Enter B/L number" required>

      <label for="email">Customer Emails</label>
      <input type="text" name="email" id="email" placeholder="Enter multiple emails separated by commas" required>

      <button type="submit">Save & Send Email</button>
    </form>
  </div>
</main>

<footer>
  &copy; 2025 CSC Shipping System |
  <a href="#">Facebook</a> |
  <a href="#">Twitter</a> |
  <a href="#">LinkedIn</a>
</footer>

<script>
  setTimeout(() => {
    document.querySelectorAll('.alert-success, .alert-error').forEach(el => el.style.display='none');
  }, 5000);
</script>

</body>
</html>
