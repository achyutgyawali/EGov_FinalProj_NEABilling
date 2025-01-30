<?php
session_start();
require '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billId = $_POST['bill_id'];
    $totalAmount = $_POST['total_amount'];
    $paymentMethodId = $_POST['payment_method'];
    $paymentDate = date('Y-m-d');

    // Simulate payment processing
    $isPaymentSuccessful = true;

    if ($isPaymentSuccessful) {
        // Update bill status to 'Paid'
        $updateBillQuery = "UPDATE bills SET status = 'Paid' WHERE id = $billId";
        $conn->query($updateBillQuery);

        // Add payment record to payment_history
        $insertPaymentHistoryQuery = "INSERT INTO payment_history (bill_id, payment_date, amount_paid, payment_method_id) 
                                      VALUES ($billId, '$paymentDate', $totalAmount, $paymentMethodId)";
        $conn->query($insertPaymentHistoryQuery);

        $_SESSION['payment_success'] = "Payment successful for Bill No: $billId. Amount Paid: Rs. $totalAmount.";
        header("Location: payment_success.php");
        exit;
    } else {
        $_SESSION['payment_error'] = "Payment failed. Please try again.";
        header("Location: payment_error.php");
        exit;
    }
} else {
    header("Location: user_dashboard.php");
    exit;
}
?>
