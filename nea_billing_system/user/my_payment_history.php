<?php
session_start();
require '../db/connection.php';

$customerId = $_SESSION['customer_id'];
$paidBills = [];

// Fetch paid bills for the logged-in customer
$query = "SELECT bills.id AS bill_id, bills.issue_date, bills.due_date, bills.units_consumed, 
                 bills.fine, bills.discount, bills.status, customers.id AS scno, 
                 rates.rate_per_unit, 
                 (bills.units_consumed * rates.rate_per_unit + bills.fine - bills.discount) AS total_amount
          FROM bills 
          INNER JOIN customers ON bills.customer_id = customers.id
          INNER JOIN demand_types ON customers.demand_type_id = demand_types.id
          INNER JOIN rates ON demand_types.id = rates.demand_type_id
          WHERE customers.id = $customerId AND bills.status = 'Paid'";  // Filter for paid bills

$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Calculate fine/discount
        $currentDate = date('Y-m-d');
        $dueDate = $row['due_date'];
        $fine = $discount = 0;

        if ($currentDate > $dueDate) {
            $fine = $row['total_amount'] * 0.02; // 2% fine
        } else if ($currentDate < $dueDate) {
            $discount = $row['total_amount'] * 0.02; // 2% discount
        }

        $totalAmount = $row['total_amount'] + $fine - $discount;
        $row['fine'] = $fine;
        $row['discount'] = $discount;
        $row['total_amount'] = $totalAmount;

        $paidBills[] = $row;
    }
}
?>

<div class="my-payment-history-container common-styles">
    <h2 class="section-heading">My Payment History</h2>
    <?php if (!empty($paidBills)) { ?>
        <div class="table-container data-display-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Bill No</th>
                        <th>SCNO</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Units Consumed</th>
                        <th>Fine</th>
                        <th>Discount</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paidBills as $bill) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bill['bill_id']); ?></td>
                            <td><?php echo htmlspecialchars($bill['scno']); ?></td>
                            <td><?php echo htmlspecialchars($bill['issue_date']); ?></td>
                            <td><?php echo htmlspecialchars($bill['due_date']); ?></td>
                            <td><?php echo htmlspecialchars($bill['units_consumed']); ?></td>
                            <td><?php echo number_format($bill['fine'], 2); ?></td>
                            <td><?php echo number_format($bill['discount'], 2); ?></td>
                            <td><?php echo number_format($bill['total_amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($bill['status']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p class="no-data-message">No payment history found.</p>
    <?php } ?>
</div>