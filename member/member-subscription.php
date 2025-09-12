<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nexus | Member - Subscription</title>
  <link rel="stylesheet" href="member.css">
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">NEXUS</div>
    <ul class="nav-menu">
      <li><a href="member-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
      <li><a href="member-classes.php"><img src="../images/icons/dashboard-classes-icon.svg" alt="Classes" class="nav-icon"> Classes</a></li>
      <li><a href="member-my-plan.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" alt="My Plan" class="nav-icon"> My Plan</a></li>
      <li><a href="member-progress.php"><img src="../images/icons/dashboard-progress-icon.svg" alt="Progress" class="nav-icon"> Progress</a></li>
      <li class="active"><a href="member-subscription.php"><img src="../images/icons/dashboard-payment-icon.svg" alt="Subscription" class="nav-icon"> Subscription</a></li>
      <li><a href="member-profile.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Profile" class="nav-icon"> Profile</a></li>
    </ul>
    <div class="logout-container">
      <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header">
      <h2>Member Dashboard</h2>
      <div class="user-profile">
        <img src="../images/profile pictures/default-profile.svg" alt="User">
        <span>Member</span>
      </div>
    </div>

    <!-- Subscription Info -->
    <div class="subscription-info">
      <h3>Current Subscription</h3>
      <p><strong>Subscribed on:</strong> August 1, 2025</p>
      <p><strong>Next Payment:</strong> September 1, 2025</p>
      <p><strong>Status:</strong> Active</p>
    </div>

    <!-- Plans -->
    <div class="plans-container">
  <div class="plan-box">
        <div class="plan-title">Monthly Plan</div>
        <div class="plan-price">₱1200</div>
        <div class="plan-desc">Stay fit with flexibility. Perfect for short-term goals.</div>
        <a href="#" class="subscribe-btn">Choose Plan</a>
      </div>
  <div class="plan-box plan-box-orange">
        <div class="plan-title">3-Month Plan</div>
        <div class="plan-price">₱3000</div>
        <div class="plan-desc">Commit to progress. Save more with this package.</div>
        <a href="#" class="subscribe-btn">Choose Plan</a>
      </div>
  <div class="plan-box plan-box-green">
        <div class="plan-title">1-Year Plan</div>
        <div class="plan-price">₱12000</div>
        <div class="plan-desc">Go all in! Best value for your long-term fitness journey.</div>
        <a href="#" class="subscribe-btn">Choose Plan</a>
      </div>
    </div>
  </div>
</body>
</html>
