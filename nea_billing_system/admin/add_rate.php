<?php
require '../db/connection.php';

$message = '';
$status = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $demandTypeId = $_POST['demand_type'];
    $ratePerUnit = $_POST['rate_per_unit'];

    if (!empty($demandTypeId) && !empty($ratePerUnit)) {
        $demandTypeId = $conn->real_escape_string($demandTypeId);
        $ratePerUnit = $conn->real_escape_string($ratePerUnit);

        // Check if rate already exists for the given demand type
        $checkQuery = "SELECT id FROM rates WHERE demand_type_id = '$demandTypeId'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows > 0) {
            // If rate exists, update it
            $updateQuery = "UPDATE rates SET rate_per_unit = '$ratePerUnit' WHERE demand_type_id = '$demandTypeId'";
            if ($conn->query($updateQuery) === TRUE) {
                $message = "Rate updated successfully.";
            } else {
                $status = 'error';
                $message = "Error updating rate: " . $conn->error;
            }
        } else {
            // If rate does not exist, insert a new record
            $query = "INSERT INTO rates (demand_type_id, rate_per_unit) VALUES ('$demandTypeId', '$ratePerUnit')";
            if ($conn->query($query) === TRUE) {
                $message = "Rate added successfully.";
            } else {
                $status = 'error';
                $message = "Error adding rate: " . $conn->error;
            }
        }
    } else {
        $status = 'error';
        $message = "All fields are required.";
    }

    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

$query = "SELECT id, type FROM demand_types";
$demandTypes = $conn->query($query);
?>


<div class="add-rate-container">
    <h2>Add Rate</h2>
    <form action="add_rate.php" method="POST">
        <label for="demand_type">Select Demand Type:</label>
        <select id="demand_type" name="demand_type" required>
            <option value="">Select Demand Type</option>
            <?php if ($demandTypes && $demandTypes->num_rows > 0) { 
                while ($type = $demandTypes->fetch_assoc()) { ?>
                    <option value="<?php echo $type['id']; ?>"><?php echo $type['type']; ?></option>
                <?php } 
            } else { ?>
                <option>No demand types available</option>
            <?php } ?>
        </select>
        <br>
        <label for="rate_per_unit">Rate Per Unit:</label>
        <input type="number" id="rate_per_unit" name="rate_per_unit" required><br>
        <button type="submit">Add Rate</button>
    </form>
    <div id="responseMessage"></div>
</div>
