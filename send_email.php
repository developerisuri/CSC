<?php
session_start();
require_once 'config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $shipper_name = trim($_POST['shipper_name']);
    $destination = trim($_POST['destination']);
    $status = trim($_POST['status']);
    $bl_no = trim($_POST['bl_no']);
    $email = trim($_POST['email']);

    // Validate required fields
    if (empty($customer_name) || empty($shipper_name) || empty($destination) || empty($status) || empty($bl_no) || empty($email)) {
        $error = "All fields are required.";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO customer_orders (customer_name, shipper_name, destination, status, bl_no, email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $customer_name, $shipper_name, $destination, $status, $bl_no, $email);

        if ($stmt->execute()) {
            // Send email
            $to = $email;
            $subject = "CSC Shipping Details";
            $message = "
                <html>
                <head>
                <title>CSC Shipping Details</title>
                </head>
                <body>
                <p>Dear $customer_name,</p>
                <p>Your shipment details are as follows:</p>
                <ul>
                    <li><strong>Shipper Name:</strong> $shipper_name</li>
                    <li><strong>Destination:</strong> $destination</li>
                    <li><strong>Status:</strong> $status</li>
                    <li><strong>B/L Number:</strong> $bl_no</li>
                </ul>
                <p>Thank you for using CSC Shipping System.</p>
                </body>
                </html>
            ";
            $headers  = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: no-reply@cscsystem.com" . "\r\n";

            if (mail($to, $subject, $message, $headers)) {
                $success = "Customer details saved and email sent successfully!";
            } else {
                $success = "Customer details saved, but email could not be sent.";
            }

        } else {
            $error = "Error inserting data: " . $stmt->error;
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
<title>Result - CSC</title>
<style>
    body { font-family:Poppins,sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; background:#001F3F; color:#fff; text-align:center; }
    .message { background:rgba(0,0,0,0.5); padding:40px; border-radius:25px; backdrop-filter:blur(15px); }
    .success { color:#32CD32; font-weight:600; margin-bottom:15px; }
    .error { color:#FF6347; font-weight:600; margin-bottom:15px; }
    a { display:inline-block; margin-top:20px; padding:10px 20px; border-radius:20px; background:#00B3B3; color:#0a0f2c; text-decoration:none; }
    a:hover { background:#00FFFF; }
</style>
</head>
<body>
<div class="message">
    <?php
    if (!empty($success)) echo "<div class='success'>$success</div>";
    if (!empty($error)) echo "<div class='error'>$error</div>";
    ?>
    <a href="enter.php">Back to Form</a>
</div>
</body>
</html>
