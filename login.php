<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>NEXUS GYM – Login</title>
  <link rel="stylesheet" href="styles.css"/>
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

          <form action="member/member-dashboard.php" id="member-form">
            <div class="form-group">
              <label for="username">Username</label>
              <input id="username" name="username" type="text" class="input" placeholder="Enter your username" required/>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input id="password" name="password" type="password" class="input" placeholder="••••••••" required/>
            </div>
            <button type="submit" class="btn w-full">Sign In</button>
          </form>
          
          <form action="employee/employee-dashboard.php" id="employee-form">
            <div class="form-group">
              <label for="username-employee">Username</label>
              <input id="username-employee" name="username" type="text" class="input" placeholder="Enter your username" required/>
            </div>
            <div class="form-group">
              <label for="password-employee">Password</label>
              <input id="password-employee" name="password" type="password" class="input" placeholder="••••••••" required/>
            </div>
            <button type="submit" class="btn w-full">Sign In</button>
          </form>
          
          <form action="admin/admin-dashboard.php" id="admin-form">
            <div class="form-group">
              <label for="username-admin">Username</label>
              <input id="username-admin" name="username" type="text" class="input" placeholder="Enter your username" required/>
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


</body>
</html>
