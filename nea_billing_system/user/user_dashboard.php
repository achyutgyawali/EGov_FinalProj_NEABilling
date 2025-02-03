<?php
session_start();
require '../db/connection.php';

// Check if user is logged in and is a customer
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}

// Get user information from the database using the session username
$username = $_SESSION['username'];
$query = "SELECT * FROM customers WHERE username = '$username'";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <img src="../assets/logo.png" alt="Logo" class="logo">
            <div>
                <p>Welcome, <?php echo $user['full_name']; ?></p>
                <!-- <a href="../logout.php"><button>Logout</button></a> -->
            </div>
        </div>
        <div class="dashboard-body">
            <!-- Sidebar -->
            <div class="sidebar">
                <ul>
                    <li><button data-section="user/my_bill">My Bill</button></li>
                    <li><button data-section="user/my_payment_history">My Payment History</button></li>
                    <li><button data-section="user/support_center">Support Center</button></li>
                    <li><a href="../logout.php"><button>Logout</button></a></li>
                </ul>
            </div>
            <!-- Main Section -->
            <div class="main-section">
                <h2>Welcome to Nepal Electricity Authority Online Billing System</h2>
                <div class="user-info">
                    <p><strong>Full Name:</strong> <?php echo $user['full_name']; ?></p>
                    <p><strong>Contact:</strong> <?php echo $user['contact']; ?></p>
                    <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
                    <p><strong>Branch:</strong> 
                        <?php
                        $branch_id = $user['branch_id'];
                        $branchQuery = "SELECT name FROM branches WHERE id = $branch_id";
                        $branchResult = $conn->query($branchQuery);
                        $branch = $branchResult->fetch_assoc();
                        echo $branch['name'];
                        ?>
                    </p>
                    <p><strong>Demand Type:</strong> 
                        <?php
                        $demand_type_id = $user['demand_type_id'];
                        $demandQuery = "SELECT type FROM demand_types WHERE id = $demand_type_id";
                        $demandResult = $conn->query($demandQuery);
                        $demandType = $demandResult->fetch_assoc();
                        echo $demandType['type'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
