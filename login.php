<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>NEXUS GYM – Login</title>
  <link rel="stylesheet" href="styles.css"/>
  <style>
    .error-message {
      display: none;
      background-color: #ffe5e5;
      color: #ff0000;
      border-left: 5px solid #ff0000;
      padding: 10px 15px;
      margin: 10px 0;
      font-size: 14px;
      border-radius: 4px;
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }
  </style>
</head>
<body>
  <div class="login-wrap">
    <div class="login-left">
      <div class="login-header">
        <div class="logo-container">
            <img src="images/nexus-gym-icon.png" alt="Nexus Gym Logo" class="logo-img" style="width:200px;height:200px;"/>
        </div>
        <h1 class="gym-title">NEXUS GYM</h1>
      </div>
      <p class="gym-description">A comprehensive platform designed for gym operations and member engagement.</p>
      <div class="features-container">
        <div class="features-grid">
          <div class="feature-item">
            <div class="feature-icon">
                <img src="images/icons/dashboard-profile-icon.svg" alt="Profile Icon" style="width:24px;height:24px;"/>
            </div>
            <p>Member Management</p>
          </div>
          <div class="feature-item">
            <div class="feature-icon">
                <img src="images/icons/fitness-plan-icon.svg" alt="Fitness Plan Icon" style="width:24px;height:24px;"/>
            </div>
            <p>Fitness Plans</p>
          </div>
          <div class="feature-item">
            <div class="feature-icon">
                <img src="images/icons/performance-tracking-icon.svg" alt="Performance Tracking Icon" style="width:24px;height:24px;"/>
            </div>
            <p>Performance Tracking</p>
          </div>
        </div>
      </div>
    </div>
    <div class="login-right">
      <div class="login-card">
        <div class="mobile-logo">
          <div class="mobile-logo-icon">
                <img src="images/nexus-gym-icon.png" alt="Nexus Gym Logo" class="logo-img" style="width:200px;height:200px;"/>
          </div>
          <h1 class="mobile-gym-title">NEXUS GYM</h1>
        </div>
        <h2 class="signin-title">Sign In</h2>

        <div class="login-forms">
          <input type="radio" name="role" id="role-member" checked>
          <input type="radio" name="role" id="role-employee">
          <input type="radio" name="role" id="role-admin">
          
          <div class="tabgroup">
            <div class="tabs">
              <label for="role-member">Member</label>
              <label for="role-employee">Employee</label>
              <label for="role-admin">Admin</label>
            </div>
          </div>

          <!-- Modern error message -->
          <div class="error-message" id="error-msg"><?php if(isset($_SESSION['login_error'])) echo $_SESSION['login_error']; ?></div>

          <!-- Member Login -->
          <form action="login-process.php" method="POST" id="member-form">
            <input type="hidden" name="role" value="member">
            <div class="form-group">
              <label for="username">Email</label>
              <input id="username" name="username" type="text" class="input" placeholder="Enter your email" required/>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input id="password" name="password" type="password" class="input" placeholder="••••••••" required/>
            </div>
            <button type="submit" class="btn w-full">Sign In</button>
          </form>
          
          <!-- Employee Login -->
          <form action="login-process.php" method="POST" id="employee-form">
            <input type="hidden" name="role" value="employee">
            <div class="form-group">
              <label for="username-employee">Email</label>
              <input id="username-employee" name="username" type="text" class="input" placeholder="Enter your email" required/>
            </div>
            <div class="form-group">
              <label for="password-employee">Password</label>
              <input id="password-employee" name="password" type="password" class="input" placeholder="••••••••" required/>
            </div>
            <button type="submit" class="btn w-full">Sign In</button>
          </form>
          
          <!-- Admin Login -->
          <form action="login-process.php" method="POST" id="admin-form">
            <input type="hidden" name="role" value="admin">
            <div class="form-group">
              <label for="username-admin">Email</label>
              <input id="username-admin" name="username" type="text" class="input" placeholder="Enter your email" required/>
            </div>
            <div class="form-group">
              <label for="password-admin">Password</label>
              <input id="password-admin" name="password" type="password" class="input" placeholder="••••••••" required/>
            </div>
            <button type="submit" class="btn w-full">Sign In</button>
          </form>
        </div>
        <div class="forgot-password">
          <a href="#">Forgot password?</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const errorBox = document.getElementById('error-msg');
      if(errorBox.textContent.trim() !== "") {
        errorBox.style.display = 'block';
      }
    });
  </script>
  <?php unset($_SESSION['login_error']); ?>
</body>
</html>
