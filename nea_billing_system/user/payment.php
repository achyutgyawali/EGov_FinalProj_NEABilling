<?php
session_start();
require '../db/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_SESSION['bill_id'] = $_POST['bill_id'];
    $_SESSION['total_amount'] = $_POST['total_amount'];
    
    header("Location: khalti_payment.php");
    exit();
}
?>
