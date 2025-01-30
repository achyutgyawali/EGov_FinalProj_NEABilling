<?php
session_start();
require '../db/connection.php';

if (!isset($_POST['bill_id']) || !isset($_POST['total_amount'])) {
    header("Location: user_dashboard.php");
    exit;
}

$billId = $_POST['bill_id'];
$totalAmount = $_POST['total_amount'];

// Fetch available payment methods
$paymentMethodsQuery = "SELECT * FROM payment_options";
$paymentMethodsResult = $conn->query($paymentMethodsQuery);
$paymentMethods = [];
if ($paymentMethodsResult) {
    while ($row = $paymentMethodsResult->fetch_assoc()) {
        $paymentMethods[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Nepal Electricity Authority</title>
    <link rel="stylesheet" href="../css/style.css">
    
</head>
<body>
    <div class="payment-container">
        <h2>Payment for Bill No: <?php echo $billId; ?></h2>
        <p><strong>Total Amount:</strong> Rs. <?php echo number_format($totalAmount, 2); ?></p>

        <form method="POST" action="process_payment.php">
            <input type="hidden" name="bill_id" value="<?php echo $billId; ?>">
            <input type="hidden" name="total_amount" value="<?php echo $totalAmount; ?>">
            <label for="payment_method">Select Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <?php foreach ($paymentMethods as $method) { ?>
                    <option value="<?php echo $method['id']; ?>"><?php echo $method['name']; ?></option>
                <?php } ?>
            </select><br>
            <button  type="submit" >Pay Now</button>
        </form><br><br>
        <a href="user_dashboard.php"><button>Cancel</button></a>
    </div>
</body>
</html>
