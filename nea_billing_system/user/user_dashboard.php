<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}
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
            <!-- <h1>Nepal Electricity Authority</h1> -->
            <img src="../assets/logo.png" alt="Logo" class="logo">
            <div>
                <p>Welcome, <?php echo $_SESSION['username']; ?></p>
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
                <h2>Welcome to Nepal Electricity Authority Online Billing System User Dashboard</h2>
            </div>
        </div>
    </div>
</body>
</html>
