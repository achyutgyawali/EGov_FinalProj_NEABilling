<link rel = "stylesheet" href="../css/paymentdecision.css">

<?php
echo "<div id='content-wrapper'>"; // Outer wrapper

if (isset($_GET['pidx']) && isset($_GET['status']) && $_GET['status'] === 'Completed') {
    $pidx = $_GET['pidx'];
    $transactionId = $_GET['transaction_id'];
    $amount = $_GET['amount'];
    $purchaseOrderId = $_GET['purchase_order_id'];

    // Update database: Mark the bill as paid
    require_once "../db/connection.php"; // Include database connection

    $stmt = $conn->prepare("UPDATE bills SET status='Paid' WHERE id=?");
    $stmt->bind_param("i", $purchaseOrderId); // Bind the bill ID for updating
    $stmt->execute();

    echo "<div class='payment-status-container payment-status-success'>"; // Inner success div
    echo "<h2 class='payment-status-heading'>Payment Successful!</h2>";
    echo "<a href='user_dashboard.php' class='payment-status-dashboard-button'>Back to Dashboard</a>";
    echo "</div>"; // Close inner success div
} else {
    echo "<div class='payment-status-container payment-status-failure'>"; // Inner failure div
    echo "<h2 class='payment-status-heading'>Payment Failed!</h2>";
    echo "<a href='user_dashboard.php' class='payment-status-dashboard-button'>Back to Dashboard</a>";
    echo "</div>"; // Close inner failure div
}

echo "</div>"; // Close outer wrapper
?>