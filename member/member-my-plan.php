    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Nexus | Member - My Plan</title>
      <link rel="stylesheet" href="member.css">
    </head>
    <body>
      <!-- Sidebar -->
      <div class="sidebar">
        <div class="logo">NEXUS</div>
        <ul class="nav-menu">
          <li><a href="member-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" alt="Dashboard" class="nav-icon"> Dashboard</a></li>
          <li><a href="member-classes.php"><img src="../images/icons/dashboard-classes-icon.svg" alt="Classes" class="nav-icon"> Classes</a></li>
          <li class="active"><a href="member-my-plan.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" alt="My Plan" class="nav-icon"> My Plan</a></li>
          <li><a href="member-progress.php"><img src="../images/icons/dashboard-progress-icon.svg" alt="Progress" class="nav-icon"> Progress</a></li>
          <li><a href="member-subscription.php"><img src="../images/icons/dashboard-payment-icon.svg" alt="Subscription" class="nav-icon"> Subscription</a></li>
          <li><a href="member-profile.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Profile" class="nav-icon"> Profile</a></li>
        </ul>
        <div class="logout-container">
          <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" alt="Logout" class="nav-icon"> Logout</a>
        </div>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <div class="header">
          <h2>My Plan</h2>
          <div class="user-profile">
            <img src="../images/profile pictures/default-profile.svg" alt="User">
            <span>Member</span>
          </div>
        </div>

        <!-- Fitness Programs Row -->
        <div class="card-row card-row-flex card-row-gap card-row-margin-bottom">
          <!-- Box 1 -->
          <div class="card card-flex-1">
            <div class="employee-table-title">UPU (Upper Lower Program)</div>
            <ul class="ul-margin-top">
              <li><b>Day 1:</b> Chest & Shoulder, Tricep, Light Back</li>
              <li><b>Day 2:</b> Leg Day & Core</li>
              <li><b>Day 3:</b> Back & Bicep, Light Chest</li>
              <li><b>Day 4:</b> Rest</li>
              <li><b>Day 5:</b> Cardio or Shoulder</li>
            </ul>
          </div>

          <!-- Box 2 -->
          <div class="card card-flex-1">
            <div class="employee-table-title">PPL (Push, Pull, Legs)</div>
            <ul class="ul-margin-top">
              <li><b>Day 1:</b> Chest & Shoulder, Tricep</li>
              <li><b>Day 2:</b> Back & Bicep</li>
              <li><b>Day 3:</b> Legs & Core</li>
              <li><b>Day 4:</b> Rest</li>
              <li><b>Day 5:</b> Shoulder</li>
            </ul>
          </div>

          <!-- Box 3 -->
          <div class="card card-flex-1">
            <div class="employee-table-title">Coach’s Recommendation</div>
            <p class="p-margin-top">
              Personalized fitness plan tailored by the coach for your goals such as weight loss, muscle building, or endurance training.
            </p>
          </div>
        </div>

                    <!-- Motivational Quote Section -->
      <div class="card card-margin-bottom" style="text-align: center; padding: 40px; background-color: #111;">
        <div style="font-size: 32px; font-weight: bold; font-family: 'Georgia', serif; font-style: italic; color: #00f7ff; text-shadow: 0 0 8px #00f7ff, 0 0 16px #00dfff;">
          “One day or Day one”
        </div>
        <div style="margin-top: 15px; font-size: 20px; color: #00f7ff; text-shadow: 0 0 6px #00f7ff;">
          — see the available coaches
        </div>
      </div>



              <!-- See Available Coaches -->
        <div class="card">
          <div class="employee-table-title">See Available Coaches</div>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>Coach Name</th>
                  <th>Position</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                include '../db.php';
                $coachQuery = "
                    SELECT e.id, e.full_name, e.position, e.status,
                          (SELECT COUNT(*) FROM coach_availability ca WHERE ca.employee_id = e.id) AS availability_count
                    FROM employees e
                    WHERE e.status = 'Active' AND e.full_name LIKE '%Coach%'
                    ORDER BY e.full_name ASC
                ";
                $coachResult = $conn->query($coachQuery);

                if ($coachResult && $coachResult->num_rows > 0):
                    while($coach = $coachResult->fetch_assoc()): ?>
                      <tr>
                        <td><?= htmlspecialchars($coach['full_name']); ?></td>
                        <td><?= htmlspecialchars($coach['position']); ?></td>
                        <td><?= htmlspecialchars($coach['status']); ?></td>
                        <td>
                          <?php if ($coach['availability_count'] > 0): ?>
                            <a href="member-schedule.php?coach_id=<?= $coach['id']; ?>" 
                              class="btn-available" 
                              style="color: green; font-weight: bold; text-decoration: none;">
                              Available
                            </a>
                          <?php else: ?>
                            <span style="color: red; font-weight: bold;">Not Available</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endwhile;
                else: ?>
                  <tr><td colspan="4">No coaches available.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

