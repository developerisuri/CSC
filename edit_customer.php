<?php
require_once 'config.php'; // includes $conn

// Get the record
$id = $_GET['id'] ?? 0;
$id = (int)$id;

// Fetch data
$sql = "SELECT * FROM customer_orders WHERE cus_id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Record not found.");
}

$row = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['update'])) {
    $customer_name = $_POST['customer_name'];
    $shipper_name = $_POST['shipper_name'];
    $destination = $_POST['destination'];
    $status = $_POST['status'];
    $bl_no = $_POST['bl_no'];
    $email = $_POST['email'];

    $update_sql = "UPDATE customer_orders 
                   SET customer_name='$customer_name', shipper_name='$shipper_name',
                       destination='$destination', status='$status', bl_no='$bl_no', email='$email'
                   WHERE cus_id=$id";

    if ($conn->query($update_sql)) {
        echo "<script>alert('Customer details updated successfully!'); window.location='view.php';</script>";
    } else {
        echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Customer</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
<style>
body {
  background: linear-gradient(120deg, #001F3F, #004C99);
  color: #fff;
  font-family: 'Poppins', sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
form {
  background: rgba(255,255,255,0.1);
  padding: 25px;
  border-radius: 12px;
  width: 400px;
  box-shadow: 0 0 15px rgba(255,215,0,0.2);
}
h2 {
  text-align: center;
  color: #ffd700;
  margin-bottom: 20px;
}
label {
  display: block;
  margin-top: 10px;
}
input, select {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 6px;
  margin-top: 5px;
}
button {
  margin-top: 20px;
  width: 100%;
  padding: 12px;
  border: none;
  border-radius: 8px;
  background: #00e6e6;
  color: #000;
  font-weight: bold;
  cursor: pointer;
}
button:hover {
  background: #ffd700;
}
</style>
</head>
<body>
<form method="post">
  <h2>Edit Customer</h2>

  <label>Customer Name:</label>
  <input type="text" name="customer_name" value="<?= htmlspecialchars($row['customer_name']) ?>" required>

  <label>Shipper Name:</label>
  <input type="text" name="shipper_name" value="<?= htmlspecialchars($row['shipper_name']) ?>" required>

  <label>Destination:</label>
  <input type="text" name="destination" value="<?= htmlspecialchars($row['destination']) ?>" required>

  <label>Status:</label>
  <input type="text" name="status" value="<?= htmlspecialchars($row['status']) ?>" required>

  <label>B/L No:</label>
  <input type="text" name="bl_no" value="<?= htmlspecialchars($row['bl_no']) ?>" required>

  <label>Email:</label>
  <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>

  <button type="submit" name="update">Update Customer</button>
</form>
</body>
</html>
