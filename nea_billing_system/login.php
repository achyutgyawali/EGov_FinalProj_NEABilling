<?php
session_start();
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'admin') {
        header("Location: admin/admin_dashboard.php");
    } else {
        header("Location: user/user_dashboard.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nepal Electricity Authority</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="header">
            <img src="assets/logo.png" alt="Logo" class="logo">
            <!-- <h1>Nepal Electricity Authority</h1> -->
        </div>
        <form action="process_login.php" method="POST">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
            <p>New here? <a href="register.php">Register</a></p>
        </form>
    </div>
</body>
</html>
