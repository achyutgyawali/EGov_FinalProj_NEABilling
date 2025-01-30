<?php
session_start();
require '../db/connection.php';

$customerId = $_SESSION['customer_id'];
$bills = [];

// Fetch bills for the logged-in customer
$query = "SELECT bills.id AS bill_id, bills.issue_date, bills.due_date, bills.units_consumed, 
                 bills.fine, bills.discount, bills.status, customers.id AS scno, 
                 rates.rate_per_unit, 
                 (bills.units_consumed * rates.rate_per_unit + bills.fine - bills.discount) AS total_amount
          FROM bills 
          INNER JOIN customers ON bills.customer_id = customers.id
          INNER JOIN demand_types ON customers.demand_type_id = demand_types.id
          INNER JOIN rates ON demand_types.id = rates.demand_type_id
          WHERE customers.id = $customerId";

$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Compare the current date with the due date
        $currentDate = date('Y-m-d');
        $dueDate = $row['due_date'];

        // Initialize fine and discount
        $fine = 0;
        $discount = 0;

        // Add fine or discount based on payment date
        if ($currentDate > $dueDate) {
            // Payment is late, apply fine
            $fine = $row['total_amount'] * 0.02; // 2% fine
        } else {
            // Payment is on time or early, apply discount
            $discount = $row['total_amount'] * 0.02; // 2% discount
        }

        // Update the total amount with fine or discount
        $totalAmount = $row['total_amount'] + $fine - $discount;

        // Add fine and discount to the row
        $row['fine'] = $fine;
        $row['discount'] = $discount;
        $row['total_amount'] = $totalAmount;

        // Add the bill to the bills array
        $bills[] = $row;
    }
}
?>



<div class="my-bill-container">
    <h2>My Bills</h2>
    <?php if (!empty($bills)) { ?>
        <div class="table-container">
            <table>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bills as $bill) { ?>
                        <tr>
                            <td><?php echo $bill['bill_id']; ?></td>
                            <td><?php echo $bill['scno']; ?></td>
                            <td><?php echo $bill['issue_date']; ?></td>
                            <td><?php echo $bill['due_date']; ?></td>
                            <td><?php echo $bill['units_consumed']; ?></td>
                            <td><?php echo number_format($bill['fine'], 2); ?></td> <!-- Fine column -->
                            <td><?php echo number_format($bill['discount'], 2); ?></td> <!-- Discount column -->
                            <td><?php echo number_format($bill['total_amount'], 2); ?></td>
                            <td><?php echo $bill['status']; ?></td>
                            <td>
                                <?php if ($bill['status'] === 'Pending') { ?>
                                    <form method="POST" action="payment.php" >
                                        <input type="hidden" name="bill_id" value="<?php echo $bill['bill_id']; ?>">
                                        <input type="hidden" name="total_amount" value="<?php echo $bill['total_amount']; ?>">
                                        <button type="submit">Pay Now</button>
                                    </form>
                                <?php } else { ?>
                                    Paid
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p>No bills found.</p>
    <?php } ?>
</div>


