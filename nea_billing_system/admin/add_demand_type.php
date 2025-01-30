<?php
require '../db/connection.php';

$message = '';
$status = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $demand_type = $_POST['demand_type'];

    if (!empty($demand_type)) {
        $demand_type = $conn->real_escape_string($demand_type);

        $query = "INSERT INTO demand_types (type) VALUES ('$demand_type')";
        if ($conn->query($query) === TRUE) {
            $message = "Demand type added successfully.";
        } else {
            $status = 'error';
            $message = "Error adding demand type: " . $conn->error;
        }
    } else {
        $status = 'error';
        $message = "Demand type cannot be empty.";
    }

    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}
?>


<div class="add-demand-type-container">
    <h2>Add Demand Type</h2>
    <form action="add_demand_type.php" method="POST">
        <label for="demand_type">Demand Type:</label>
        <input type="text" id="demand_type" name="demand_type" required><br>
        <button type="submit">Add Demand Type</button>
    </form>
    <div id="responseMessage"></div>
</div>
