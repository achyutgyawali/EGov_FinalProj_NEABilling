<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Include database connection
require '../db/connection.php';

// Fetch report data (same as before)
$customersByDemandType = $conn->query("SELECT dt.type AS demand_type, COUNT(c.id) AS customer_count FROM customers c JOIN demand_types dt ON c.demand_type_id = dt.id GROUP BY dt.type");
$customersByBranch = $conn->query("SELECT b.name AS branch_name, COUNT(c.id) AS customer_count FROM customers c JOIN branches b ON c.branch_id = b.id GROUP BY b.name");
$paidBills = $conn->query("SELECT COUNT(id) AS paid_count FROM bills WHERE status = 'Paid'");
$pendingBills = $conn->query("SELECT COUNT(id) AS pending_count FROM bills WHERE status = 'Pending'");

// Fetch total revenue by summing total_bill_amount for all 'Paid' bills
$totalRevenueQuery = $conn->query("SELECT SUM(total_bill_amount) AS total_revenue FROM bills WHERE status = 'Paid'");
$totalRevenueData = $totalRevenueQuery->fetch_assoc();
$totalRevenueAmount = $totalRevenueData['total_revenue'] ?: 0; // If no paid bills, set revenue to 0
?>
<div>
<h2>System Report</h2>

<!-- Report Tables will be displayed dynamically here -->
<div id="report-section">
    <table border="1">
        <thead>
            <tr>
                <th>Demand Type</th>
                <th>Number of Customers</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $customersByDemandType->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['demand_type']; ?></td>
                    <td><?php echo $row['customer_count']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>

    <table border="1">
        <thead>
            <tr>
                <th>Branch Name</th>
                <th>Number of Customers</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $customersByBranch->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['branch_name']; ?></td>
                    <td><?php echo $row['customer_count']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>

    <table border="1">
        <thead>
            <tr>
                <th>Bill Status</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $paid = $paidBills->fetch_assoc();
                $pending = $pendingBills->fetch_assoc();
            ?>
            <tr>
                <td>Paid</td>
                <td><?php echo $paid['paid_count']; ?></td>
            </tr>
            <tr>
                <td>Pending</td>
                <td><?php echo $pending['pending_count']; ?></td>
            </tr>
        </tbody>
    </table>

    <br>

    <table border="1">
        <thead>
            <tr>
                <th>Total Revenue Generated</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><?php echo 'NPR ' . number_format($totalRevenueAmount, 2); ?></td>            
        </tr>
        </tbody>
    </table>
</div>
</div>
</div>