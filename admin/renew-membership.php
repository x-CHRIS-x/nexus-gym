<?php
session_start();
require_once '../includes/db_connection.php';

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'employee' && $_SESSION['role'] !== 'admin')) {
    header("Location: ../login.php");
    exit();
}

// Check if member ID is provided
if (!isset($_GET['id'])) {
    header("Location: admin-members.php");
    exit();
}

$memberId = $_GET['id'];

// Fetch member details
$query = "SELECT * FROM members WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $memberId);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

if (!$member) {
    header("Location: admin-members.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $duration = $_POST['duration'];
    $amount = $_POST['amount'];
    
    // Update member status to active
    $updateQuery = "UPDATE members SET status = 'active' WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $memberId);
    
    if ($stmt->execute()) {
        $successMessage = "Membership renewed successfully!";
    } else {
        $errorMessage = "Error renewing membership. Please try again.";
    }
}

$plans = [
    '1' => ['name' => '1 Month', 'price' => 1200],
    '3' => ['name' => '3 Months', 'price' => 3000],
    '12' => ['name' => '1 Year', 'price' => 12000]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renew Membership - Nexus Gym</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        .renewal-form {
            background: #1b1b20ff;
            padding: 32px 24px 24px 24px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #35373bff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
        .form-header {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 32px;
            color: #fff;
            display: flex;
            align-items: center;
        }
        .form-header::before {
            content: "\1F4DD ";
            font-size: 1em;
            margin-right: 10px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 18px;
        }
        .form-group {
            margin-bottom: 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 1.1em;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            background: #232b3a;
            border: 1px solid #2c3446;
            color: #fff;
            font-size: 1em;
        }
        .form-group input[readonly] {
            background: #232b3a;
            cursor: not-allowed;
        }
        .amount-label {
            display: block;
            color: #fff;
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .total-amount {
            background: #232b3a;
            border-radius: 6px;
            border: 1px solid #2c3446;
            padding: 12px;
            margin-bottom: 18px;
        }
        .total-amount .amount {
            font-size: 1em;
            color: #dc2626;
            font-weight: bold;
        }
        .submit-btn {
            background: #2563eb;
            color: white;
            padding: 12px 22px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 600;
            transition: background-color 0.3s;
            margin-top: 8px;
        }
        .submit-btn:hover {
            background: #1d4ed8;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        .alert-success {
            background: #198754;
            color: white;
        }
        .alert-danger {
            background: #dc3545;
            color: white;
        }
        .full-width {
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
            <li><a href="admin-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li class="active"><a href="admin-members.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
            <li><a href="admin-employees.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Employees" class="nav-icon"> Employees</a></li>
            <li><a href="admin-settings.php"><img src="../images/icons/dashboard-settings-icon.svg" alt="Settings" class="nav-icon"> Settings</a></li>
        </ul>
        <div class="logout-container">
            <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Renew Membership</h2>
        </div>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <div class="renewal-form">
            <div class="form-header">Renew Membership Form</div>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($member['full_name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Member ID</label>
                        <input type="text" value="<?php echo htmlspecialchars($member['id']); ?>" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Membership Type</label>
                        <input type="text" value="<?php echo htmlspecialchars($member['membership_type']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="duration">Renew Upto</label>
                        <select id="duration" name="duration" onchange="updateAmount()">
                            <option value="1">1 Month - ₱1,200</option>
                            <option value="3">3 Months - ₱3,000</option>
                            <option value="12">1 Year - ₱12,000</option>
                        </select>
                    </div>
                </div>
                <label class="amount-label">Total Amount</label>
                <div class="total-amount">
                    <span class="amount" id="totalAmount">₱1,200.00</span>
                    <input type="hidden" name="amount" id="amountInput" value="1200">
                </div>
                <button type="submit" class="submit-btn">Renew</button>
            </form>
        </div>

    </div>

    <script>
    function updateAmount() {
        const duration = document.getElementById('duration').value;
        const prices = {
            '1': 1200,
            '3': 3000,
            '12': 12000
        };
        const amount = prices[duration];
        document.getElementById('totalAmount').textContent = '₱' + amount.toLocaleString('en-US', {minimumFractionDigits: 2});
        document.getElementById('amountInput').value = amount;
    }
    </script>
</body>
</html>