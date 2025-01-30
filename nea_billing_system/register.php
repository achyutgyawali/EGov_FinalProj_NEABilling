<?php
require 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $branch_id = $_POST['branch'];
    $demand_type_id = $_POST['demand_type'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Check if username is 'admin'
    if ($username == 'admin') {
        die("Error: Username 'admin' is reserved.");
    }

    // Insert customer
    $query = "INSERT INTO customers (full_name, contact, address, branch_id, demand_type_id, username, password)
              VALUES ('$full_name', '$contact', '$address', $branch_id, $demand_type_id, '$username', '$password')";

    if ($conn->query($query) === TRUE) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nepal Electricity Authority</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="register-container">
        <form action="" method="POST">
            <h2>Register</h2>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="text" name="contact" placeholder="Contact" required>
            <input type="text" name="address" placeholder="Address" required>
            <select name="branch" required>
                <option value="">Select Branch</option>
                <?php
                $branches = $conn->query("SELECT * FROM branches");
                while ($branch = $branches->fetch_assoc()) {
                    echo "<option value='{$branch['id']}'>{$branch['name']}</option>";
                }
                ?>
            </select>
            <select name="demand_type" required>
                <option value="">Select Demand Type</option>
                <?php
                $demandTypes = $conn->query("SELECT * FROM demand_types");
                while ($demandType = $demandTypes->fetch_assoc()) {
                    echo "<option value='{$demandType['id']}'>{$demandType['type']}</option>";
                }
                ?>
            </select>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
