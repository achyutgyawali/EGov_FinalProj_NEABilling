<?php
require '../db/connection.php';

$message = '';
$status = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_name = $_POST['branch_name'];

    if (!empty($branch_name)) {
        $branch_name = $conn->real_escape_string($branch_name);

        $query = "INSERT INTO branches (name) VALUES ('$branch_name')";
        if ($conn->query($query) === TRUE) {
            $message = "Branch added successfully.";
        } else {
            $status = 'error';
            $message = "Error adding branch: " . $conn->error;
        }
    } else {
        $status = 'error';
        $message = "Branch name cannot be empty.";
    }

    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}
?>


<div class="add-branch-container">
    <h2>Add Branch</h2>
    <form action="add_branch.php" method="POST">
        <label for="branch_name">Branch Name:</label>
        <input type="text" id="branch_name" name="branch_name" required>
        <button type="submit">Add Branch</button>
    </form>
    <div id="responseMessage"></div>
</div>
