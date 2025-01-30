<?php
require '../db/connection.php';

$message = '';
$status = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_option = $_POST['payment_option'];

    if (!empty($payment_option)) {
        $payment_option = $conn->real_escape_string($payment_option);

        $query = "INSERT INTO payment_options (name) VALUES ('$payment_option')";
        if ($conn->query($query) === TRUE) {
            $message = "Payment option added successfully.";
        } else {
            $status = 'error';
            $message = "Error adding payment option: " . $conn->error;
        }
    } else {
        $status = 'error';
        $message = "Payment option cannot be empty.";
    }

    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}
?>


<div class="add-payment-option-container">
    <h2>Add Payment Option</h2>
    <form action="add_payment_option.php" method="POST">
        <label for="payment_option">Payment Option:</label>
        <input type="text" id="payment_option" name="payment_option" required><br>
        <button type="submit">Add Payment Option</button>
    </form>
    <div id="responseMessage"></div>
</div>
