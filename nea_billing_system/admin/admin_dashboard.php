<?php
// Start session and check for admin privileges
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <!-- <h1>Nepal Electricity Authority</h1> -->
            <img src="../assets/logo.png" alt="Logo" class="logo">
            <div>
                <p>Welcome, Admin</p>
                <!-- <a href="../logout.php"><button>Logout</button></a> -->
            </div>
        </div>
        <div class="dashboard-body">
            <!-- Sidebar -->
            <div class="sidebar">
                <ul>
                    <!-- <li><button data-section="admin/search_customer">Search Customer</button></li> -->
                    <li><button data-section="admin/report">Generate Report</button></li>
                    <li><button data-section="admin/generate_bill">Generate Bill</button></li>
                    <li><button data-section="admin/add_branch">Add Branch</button></li>
                    <li><button data-section="admin/add_payment_option">Add Payment Option</button></li>
                    <li><button data-section="admin/add_demand_type">Add Demand Type</button></li>
                    <li><button data-section="admin/add_rate">Add Rate</button></li>
                    <li><a href="../logout.php"><button>Logout</button></a></li>
                </ul>
            </div>
            <!-- Main Section -->
            <div class="main-section">
                <h2>Welcome to the Admin Dashboard</h2>
            </div>
        </div>
    </div>
</body>
</html>
