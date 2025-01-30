<?php
session_start();
if (!isset($_SESSION['payment_error'])) {
    header("Location: user_dashboard.php");
    exit;
}

$errorMessage = $_SESSION['payment_error'];
unset($_SESSION['payment_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="payment-error-container">
        <h2>Payment Failed</h2>
        <p><?php echo $errorMessage; ?></p>
        <a href="user_dashboard.php"><button>Back to My Dashboard</button></a>
    </div>
</body>
</html>
