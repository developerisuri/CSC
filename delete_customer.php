<?php
require_once 'config.php';

$id = $_GET['id'] ?? 0;
$id = (int)$id;

if ($id > 0) {
    $delete_sql = "DELETE FROM customer_orders WHERE cus_id = $id";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('Customer deleted successfully!'); window.location='view.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "'); window.location='view.php';</script>";
    }
} else {
    echo "<script>alert('Invalid ID'); window.location='view.php';</script>";
}
$conn->close();
?>
