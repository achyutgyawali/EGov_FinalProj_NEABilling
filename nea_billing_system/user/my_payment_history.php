<?php
session_start();
require '../db/connection.php';

$customerId = $_SESSION['customer_id'];
$paymentHistory = [];

// Fetch payment history for the logged-in customer
$query = "SELECT payment_history.payment_date, payment_history.amount_paid, payment_options.name AS payment_method,
          bills.id AS bill_id, bills.issue_date, bills.due_date
          FROM payment_history
          INNER JOIN bills ON payment_history.bill_id = bills.id
          INNER JOIN payment_options ON payment_history.payment_method_id = payment_options.id
          WHERE bills.customer_id = $customerId";

$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $paymentHistory[] = $row;
    }
}
?>

<div class="my-payment-history-container">
    <h2>My Payment History</h2>
    <?php if (!empty($paymentHistory)) { ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Bill No</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Payment Date</th>
                        <th>Amount Paid</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paymentHistory as $history) { ?>
                        <tr>
                            <td><?php echo $history['bill_id']; ?></td>
                            <td><?php echo $history['issue_date']; ?></td>
                            <td><?php echo $history['due_date']; ?></td>
                            <td><?php echo $history['payment_date']; ?></td>
                            <td><?php echo number_format($history['amount_paid'], 2); ?></td>
                            <td><?php echo $history['payment_method']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p>No payment history found.</p>
    <?php } ?>
</div>
