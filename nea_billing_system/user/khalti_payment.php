<?php
session_start();
if (!isset($_SESSION['bill_id']) || !isset($_SESSION['total_amount'])) {
    header("Location: index.php");
    exit();
}

$billId = $_SESSION['bill_id'];
$totalAmount = $_SESSION['total_amount'] * 100;  // Ensure the amount is in paisa

// Check if the amount is valid (greater than or equal to 100 paisa)
if ($totalAmount < 100) {
    echo "Amount should be at least 100 paisa.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay with Khalti</title>
    <script src="https://khalti.com/static/khalti-checkout.js"></script>
</head>
<body>

<h2>Pay Now with Khalti</h2>
<p>Bill ID: <?php echo $billId; ?></p>
<p>Total Amount: Rs. <?php echo number_format($totalAmount / 100, 2); ?></p>

<button id="pay-button">Pay with Khalti</button>

<script>
    var config = {
        publicKey: "c0da594d0ee24312939137d43f0a909a", // Your Khalti public key
        productIdentity: "<?php echo $billId; ?>",
        productName: "Electricity Bill Payment",
        productUrl: "http://localhost/egov_finalproject/nea_billing_system/user/khalti_payment.php", // Your payment page URL
        amount: <?php echo $totalAmount; ?>,  // Ensure amount is in paisa (integer)
        eventHandler: {
            onSuccess(payload) {
                console.log('Payment Success Payload:', payload);
                fetch("verify_khalti.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Payment Successful!");
                        window.location.href = "success.php"; // Redirect to success page
                    } else {
                        alert("Payment Failed!");
                    }
                });
            },
            onError(error) {
                console.log("Payment Error: ", error);
                alert("Payment failed! Please check console for details.");
            },
            onClose() {
                console.log("Payment closed.");
            }
        }
    };

    var checkout = new KhaltiCheckout(config);
    document.getElementById("pay-button").onclick = function () {
        checkout.show({amount: config.amount});
    };
</script>

</body>
</html>
