<?php
session_start();
require_once '../includes/db_connection.php';

// Check if user is logged in and has appropriate role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit();
}

// Check if member ID is provided
if (!isset($_GET['id'])) {
    header("Location: employee-members.php");
    exit();
}

$memberId = $_GET['id'];

// Check if membership is eligible for renewal (expired or within 3 days of expiration)
$checkEligibilityQuery = "SELECT 
    DATEDIFF(membership_end_date, CURDATE()) as days_until_expiry,
    status
    FROM members 
    WHERE id = ?";
$stmt = $conn->prepare($checkEligibilityQuery);
$stmt->bind_param("i", $memberId);
$stmt->execute();
$eligibility = $stmt->get_result()->fetch_assoc();

if ($eligibility['status'] === 'Active' && $eligibility['days_until_expiry'] > 3) {
    $_SESSION['error_message'] = "Membership can only be renewed when expired or within 3 days of expiration.";
    header("Location: employee-members.php");
    exit();
}

// Fetch member details
$query = "SELECT * FROM members WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $memberId);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

if (!$member) {
    header("Location: employee-members.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $duration = $_POST['duration'];
    $amount = $_POST['amount'];
    $membershipType = $_POST['membershipType'];
    
    // Calculate new membership end date from today
    $today = new DateTime();
    $new_end_date = $today->modify("+{$duration} months")->format('Y-m-d');
    
    // Start transaction
    $conn->begin_transaction();
    try {
        // Update member status, end date and membership type
        $updateQuery = "UPDATE members SET status = 'Active', membership_end_date = ?, membership_type = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssi", $new_end_date, $membershipType, $memberId);
        $stmt->execute();

        // Get the plan ID based on duration and membership type
        $planQuery = "SELECT id FROM membership_plans WHERE duration_months = ?";
        $stmt = $conn->prepare($planQuery);
        $stmt->bind_param("i", $duration);
        $stmt->execute();
        $planResult = $stmt->get_result();
        $plan = $planResult->fetch_assoc();
        $planId = $plan['id'];

        // Insert subscription record
        $subscriptionQuery = "INSERT INTO member_subscriptions (member_id, plan_id, start_date, end_date, status, payment_status, amount_paid) VALUES (?, ?, CURRENT_DATE, ?, 'active', 'paid', ?)";
        $stmt = $conn->prepare($subscriptionQuery);
        $stmt->bind_param("iiss", $memberId, $planId, $new_end_date, $amount);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        $successMessage = "Membership renewed successfully! New expiry date: " . date('Y-m-d', strtotime($new_end_date));
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $errorMessage = "Error renewing membership. Please try again.";
    }
}

$plans = [
    'standard' => [
        '1' => ['name' => '1 Month', 'price' => 350],
        '3' => ['name' => '3 Months', 'price' => 960],
        '12' => ['name' => '1 Year', 'price' => 3800]
    ],
    'premium' => [
        '1' => ['name' => '1 Month', 'price' => 450],
        '3' => ['name' => '3 Months', 'price' => 1250],
        '12' => ['name' => '1 Year', 'price' => 5100]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renew Membership - Nexus Gym</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="employee.css">
    <style>
        .renewal-form {
            background: #1b1b20ff;
            padding: 32px 24px 24px 24px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid #35373bff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            position: relative;
        }
        .back-button {
            position: absolute;
            top: 24px;
            right: 24px;
            background: #2c3446;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .back-button:hover {
            background: #374151;
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
            <li><a href="employee-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
            <li class="active"><a href="employee-members.php"><img src="../images/icons/dashboard-members-icon.svg" alt="Members" class="nav-icon"> Members</a></li>
            <li><a href="employee-add-member.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Add Member" class="nav-icon"> Add Member</a></li>
            <li><a href="employee-fitness-plans.php"><img src="../images/icons/fitness-plan-icon.svg" alt="Fitness Plans" class="nav-icon"> Fitness Plans</a></li>
            <li><a href="employee-schedule.php"><img src="../images/icons/clock-icon.svg" alt="Schedule" class="nav-icon"> Schedule</a></li>
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
            <a href="employee-members.php" class="back-button">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Members
            </a>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($member['first_name'].' ' .$member['last_name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Member ID</label>
                        <input type="text" value="<?php echo htmlspecialchars($member['id']); ?>" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="membershipType">Membership Type</label>
                        <select id="membershipType" name="membershipType" onchange="updateAmount()">
                            <option value="Standard" <?php echo $member['membership_type'] == 'Standard' ? 'selected' : ''; ?>>Standard</option>
                            <option value="Premium" <?php echo $member['membership_type'] == 'Premium' ? 'selected' : ''; ?>>Premium</option>
                        </select>
                        <small style="color: #666; display: block; margin-top: 4px;">(Current: <?php echo htmlspecialchars($member['membership_type']); ?>)</small>
                    </div>
                    <div class="form-group">
                        <label for="duration">Renew Upto</label>
                        <select id="duration" name="duration" onchange="updateAmount()">
                            <option value="1">1 Month</option>
                            <option value="3">3 Months</option>
                            <option value="12">1 Year</option>
                        </select>
                    </div>
                </div>
                <label class="amount-label">Total Amount</label>
                <div class="total-amount">
                    <span class="amount" id="totalAmount">₱450</span>
                    <input type="hidden" name="amount" id="amountInput" value="450">
                </div>
                <button type="submit" class="submit-btn" id="renewBtn" <?php echo isset($successMessage) ? 'disabled style="background: #666; cursor: not-allowed;"' : ''; ?>>
                    <?php echo isset($successMessage) ? 'Membership Renewed' : 'Renew'; ?>
                </button>
            </form>
        </div>
    </div>

    <script>
    // Prevent form resubmission on page refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    // Disable form submission if already successful
    <?php if (isset($successMessage)): ?>
    document.querySelector('form').onsubmit = function(e) {
        e.preventDefault();
        return false;
    };
    <?php endif; ?>
    function updateAmount() {
        const duration = document.getElementById('duration').value;
        const membershipType = document.getElementById('membershipType').value.toLowerCase();
        const prices = {
            'standard': {
                '1': 350,
                '3': 960,
                '12': 3800
            },
            'premium': {
                '1': 450,
                '3': 1250,
                '12': 5100
            }
        };
        
        const amount = prices[membershipType][duration];
        document.getElementById('totalAmount').textContent = '₱' + amount.toLocaleString();
        document.getElementById('amountInput').value = amount;
    }
    
    // Initialize price on page load
    updateAmount();
    </script>
</body>
</html>