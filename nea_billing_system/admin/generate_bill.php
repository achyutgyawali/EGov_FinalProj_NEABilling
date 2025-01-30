<?php
require '../db/connection.php';

$message = '';
$status = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scno = $_POST['scno'];
    $issue_date = $_POST['issue_date'];
    $due_date = $_POST['due_date'];
    $current_reading = $_POST['current_reading'];

    // Get customer data to calculate the bill, including previous reading
    $customerQuery = "SELECT customers.id, demand_types.id AS demand_type_id, 
                             rates.rate_per_unit, bills.current_reading AS previous_reading
                      FROM customers
                      INNER JOIN demand_types ON customers.demand_type_id = demand_types.id
                      INNER JOIN rates ON demand_types.id = rates.demand_type_id
                      LEFT JOIN bills ON customers.id = bills.customer_id
                      WHERE customers.id = $scno ORDER BY bills.issue_date DESC LIMIT 1";

    $result = $conn->query($customerQuery);
    if ($result && $result->num_rows > 0) {
        $customer = $result->fetch_assoc();

        // Calculate units consumed (difference between current reading and previous reading)
        $units_consumed = $current_reading - $customer['previous_reading'];
        
        if ($units_consumed < 0) {
            $status = 'error';
            $message = "Current reading cannot be less than the previous reading.";
        } else {
            // Calculate total amount based on units consumed
            $total_amount = $units_consumed * $customer['rate_per_unit'];

            // Insert new bill record with the current reading
            $insertBill = "INSERT INTO bills (customer_id, issue_date, due_date, current_reading, units_consumed, status)
                           VALUES ({$customer['id']}, '$issue_date', '$due_date', $current_reading, $units_consumed, 'Pending')";

            if ($conn->query($insertBill) === TRUE) {
                $message = "Bill generated successfully for SCNO: $scno. Total Amount: $total_amount";
            } else {
                $status = 'error';
                $message = "Error generating bill: " . $conn->error;
            }
        }
    } else {
        $status = 'error';
        $message = "Customer not found or rate not defined.";
    }

    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}
?>

<div class="generate-bill-container">
    <h2>Generate Bill</h2>
    <form action="generate_bill.php" method="POST">
                <label for="scno">SCNO:</label>
                <input type="text" id="scno" name="scno" required>
                <br>
            
                <label for="issue_date">Issue Date:</label>
                <input type="date" id="issue_date" name="issue_date" required>
                <br>
           
                <label for="due_date">Due Date:</label>
                <input type="date" id="due_date" name="due_date" required>
                <br>
            
                <label for="current_reading">Current Reading:</label>
                <input type="number" id="current_reading" name="current_reading" required>
                <br>
                
                <button type="submit">Generate Bill</button>
            
    </form>
    <div id="responseMessage"></div>
</div>
