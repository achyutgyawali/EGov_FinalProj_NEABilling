<?php
session_start();
if (!isset($_SESSION['payment_success'])) {
    header("Location: user_dashboard.php");
    exit;
}

$successMessage = $_SESSION['payment_success'];
unset($_SESSION['payment_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="payment-success-container">
        <h2>Payment Successful</h2>
        <p><?php echo $successMessage; ?></p>
        <a href="user_dashboard.php"><button>Back to My Dashboard</button></a>
    </div>
</body>
</html>
