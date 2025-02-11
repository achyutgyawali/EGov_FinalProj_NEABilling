<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $billId = $_POST['bill_id'];
    $totalAmount = $_POST['total_amount'] * 100; // Convert to paisa

    $secretKey = "Key ddbee9aa377941e9ae768eee74d58a3b"; // Ensure "Key " prefix

    $payload = json_encode([
        "return_url" => "http://localhost/egov_finalproject/nea_billing_system/user/payment_success.php",
        "website_url" => "http://localhost/egpv_finalproject/nea_billing_system/",
        "amount" => $totalAmount,
        "purchase_order_id" => $billId,
        "purchase_order_name" => "Electricity Bill Payment",
        "customer_info" => [
            "name" => "Test User",
            "email" => "test@example.com",
            "phone" => "9800000000"
        ]
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://dev.khalti.com/api/v2/epayment/initiate/"); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $secretKey",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if ($httpCode === 200 && isset($responseData["payment_url"])) {
        header("Location: " . $responseData["payment_url"]);
        exit();
    } else {
        echo "Error initiating payment: " . $response;
    }
} else {
    header("Location: user_dashboard.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay with Khalti</title>
    <link rel="stylesheet" href="../css/user_components.css">
    <script src="https://khalti.com/static/khalti-checkout.js"></script>
</head>
<body>
    <div class="payment-container common-styles">
        <h2 class="section-heading">Pay Now with Khalti</h2>
        <div class="payment-details data-display-card">
            <p>Bill ID: <span class="data-value"><?php echo htmlspecialchars($billId); ?></span></p>
            <p>Total Amount: Rs. <span class="data-value"><?php echo number_format($totalAmount / 100, 2); ?></span></p>

            <button id="pay-button" class="pay-button">Pay with Khalti</button>
        </div>
        <a href="user_dashboard.php" class="dashboard-button">Back to Dashboard</a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Khalti Payment Page Loaded");

            var config = {
                publicKey: "1ad3f4f426134c0fad08b6d25ffda459", // Your Khalti public key
                productIdentity: "<?php echo $billId; ?>",
                productName: "Electricity Bill Payment",
                productUrl: "http://localhost/egov_finalproject/nea_billing_system/user/khalti_payment.php", 
                amount: <?php echo $totalAmount; ?>,  // Ensure amount is in paisa
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
                        console.error("Payment Error: ", error);
                        alert("Payment failed! Please try again.");
                    },
                    onClose() {
                        console.log("Payment closed.");
                    }
                }
            };

            var checkout = new KhaltiCheckout(config);

            document.getElementById("pay-button").addEventListener("click", function() {
                console.log("Pay button clicked, opening Khalti Checkout...");
                checkout.show({ amount: config.amount });
            });
        });
    </script>
</body>
</html>