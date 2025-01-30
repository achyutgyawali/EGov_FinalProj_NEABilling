<?php
session_start();
require 'db/connection.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // MD5 used for simplicity

    // Admin login
    $adminQuery = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $adminResult = $conn->query($adminQuery);

    if ($adminResult->num_rows > 0) {
        $_SESSION['user_type'] = 'admin';
        $_SESSION['username'] = $username;
        header("Location: admin/admin_dashboard.php");
        exit;
    }

    // Customer login
    $customerQuery = "SELECT * FROM customers WHERE username = '$username' AND password = '$password'";
    $customerResult = $conn->query($customerQuery);

    if ($customerResult->num_rows > 0) {
        $customer = $customerResult->fetch_assoc();
        $_SESSION['user_type'] = 'customer';
        $_SESSION['username'] = $username;
        $_SESSION['customer_id'] = $customer['id'];
        header("Location: user/user_dashboard.php");
        exit;
    }

    // Invalid login
    $_SESSION['error'] = "Invalid username or password.";
    header("Location: login.php");
    exit;
}
?>
