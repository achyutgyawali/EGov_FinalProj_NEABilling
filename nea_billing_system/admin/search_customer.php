<?php
require '../db/connection.php';

$searchResults = [];
$message = '';
$status = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchKey = $_POST['search_key'] ?? '';
    $searchBy = $_POST['search_by'] ?? '';

    // Debugging: Check POST data
    echo 'Search Key: ' . $searchKey;
    echo 'Search By: ' . $searchBy;

    // Check if search key and search criteria are provided
    if (!empty($searchKey) && !empty($searchBy)) {
        // Sanitize the input to prevent SQL injection
        $searchKey = $conn->real_escape_string($searchKey);

        // Prepare the base query
        $query = "SELECT customers.id AS scno, customers.full_name, customers.contact, customers.address,
                         branches.name AS branch_name, demand_types.type AS demand_type
                  FROM customers
                  INNER JOIN branches ON customers.branch_id = branches.id
                  INNER JOIN demand_types ON customers.demand_type_id = demand_types.id
                  WHERE ";

        // Append the appropriate condition based on search criteria
        if ($searchBy === 'name') {
            $query .= "customers.full_name LIKE '%$searchKey%'";
        } elseif ($searchBy === 'contact') {
            $query .= "customers.contact LIKE '%$searchKey%'";
        } elseif ($searchBy === 'scno') {
            $query .= "customers.id = '$searchKey'";
        } elseif ($searchBy === 'branch') {
            $query .= "branches.name LIKE '%$searchKey%'";
        }

        // Debugging: Check the query being executed
        echo 'Executing Query: ' . $query;

        // Execute the query
        $result = $conn->query($query);

        if (!$result) {
            die("Query failed: " . $conn->error);
        }

        if ($result && $result->num_rows > 0) {
            // Fetch all results
            while ($row = $result->fetch_assoc()) {
                $searchResults[] = $row;
            }
        } else {
            // No results found
            $status = 'error';
            $message = "No customers found matching your search.";
        }
    } else {
        // Missing search criteria or key
        $status = 'error';
        $message = "Please enter search key and select search criteria.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Customer</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="search-container">
        <h2>Search Customer</h2>
        <form method="POST">
            <label for="search_key">Search Key:</label>
            <input type="text" id="search_key" name="search_key" value="<?php echo isset($searchKey) ? $searchKey : ''; ?>" required>
            <br>
            <label for="search_by">Search By:</label>
            <select id="search_by" name="search_by" required>
                <option value="name" <?php echo isset($searchBy) && $searchBy === 'name' ? 'selected' : ''; ?>>Name</option>
                <option value="contact" <?php echo isset($searchBy) && $searchBy === 'contact' ? 'selected' : ''; ?>>Contact</option>
                <option value="scno" <?php echo isset($searchBy) && $searchBy === 'scno' ? 'selected' : ''; ?>>SCNO</option>
                <option value="branch" <?php echo isset($searchBy) && $searchBy === 'branch' ? 'selected' : ''; ?>>Branch</option>
            </select>
            <br>
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($message)) { ?>
            <p class="message <?php echo $status; ?>"><?php echo $message; ?></p>
        <?php } ?>

        <?php if (!empty($searchResults)) { ?>
            <h3>Search Results</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>SCNO</th>
                            <th>Full Name</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Branch</th>
                            <th>Demand Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($searchResults as $result) { ?>
                            <tr>
                                <td><?php echo $result['scno']; ?></td>
                                <td><?php echo $result['full_name']; ?></td>
                                <td><?php echo $result['contact']; ?></td>
                                <td><?php echo $result['address']; ?></td>
                                <td><?php echo $result['branch_name']; ?></td>
                                <td><?php echo $result['demand_type']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</body>
</html>
