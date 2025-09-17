<?php
include '../db.php';
session_start();

// Logged-in employee ID
$employee_id = $_SESSION['employee_id'] ?? 1;

// Fetch coaches (only employees with "Coach" in their name)
$coachQuery = "
    SELECT e.id, e.full_name, e.position, e.status,
           (SELECT COUNT(*) FROM coach_availability ca WHERE ca.employee_id = e.id) AS availability_count
    FROM employees e
    WHERE e.status = 'Active' AND e.full_name LIKE '%Coach%'
    ORDER BY e.full_name ASC
";
$coachResult = $conn->query($coachQuery);

// Fetch notifications for this employee
$notifQuery = "
    SELECT n.id, n.message, n.created_at, m.full_name AS member_name, e.full_name AS employee_name
    FROM notifications n
    LEFT JOIN members m ON n.member_id = m.id
    LEFT JOIN employees e ON n.employee_id = e.id
    WHERE n.employee_id = ?
    ORDER BY n.created_at DESC
    LIMIT 10
";
$stmtNotif = $conn->prepare($notifQuery);
$stmtNotif->bind_param("i", $employee_id);
$stmtNotif->execute();
$notifResult = $stmtNotif->get_result();
$stmtNotif->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nexus | Employee - Fitness Plans</title>
<link rel="stylesheet" href="employee.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
.toggle-btn {
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    color: white;
    border-radius: 4px;
}
.available { background-color: green; }
.not-available { background-color: red; }
.popup {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #4caf50;
    color: #fff;
    padding: 12px 18px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.3);
    display: none;
    z-index: 9999;
    font-size: 14px;
}
</style>
</head>
<body>
<div class="sidebar">
  <div class="logo">NEXUS</div>
  <ul class="nav-menu">
    <li><a href="employee-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" class="nav-icon"> Dashboard</a></li>
    <li><a href="employee-members.php"><img src="../images/icons/dashboard-members-icon.svg" class="nav-icon"> Members</a></li>
    <li><a href="employee-schedule.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" class="nav-icon"> Schedule</a></li>
    <li class="active"><a href="employee-fitness-plans.php"><img src="../images/icons/dashboard-My_Plan-icon.svg" class="nav-icon"> Fitness Plans</a></li>
  </ul>
  <div class="logout-container">
    <a href="../login.php" class="logout-btn"><img src="../images/icons/logout-icon.svg" class="nav-icon"> Logout</a>
  </div>
</div>

<div class="main-content">
  <div class="header">
    <h2>Fitness Plans</h2>
    <div class="user-profile">
      <img src="../images/profile pictures/default-profile.svg" alt="User">
      <span>Employee</span>
    </div>
  </div>

  <!-- Fitness Programs -->
  <div class="card-row card-row-flex card-row-gap card-row-margin-bottom">
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
    <div class="card card-flex-1">
      <div class="employee-table-title">Coach’s Recommendation</div>
      <p class="p-margin-top">
        Personalized fitness plan tailored by the coach for specific goals such as weight loss, muscle building, or endurance training.
      </p>
    </div>
  </div>

  <!-- Available Coaches -->
  <div class="card">
    <div class="employee-table-title">Available Coaches</div>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Coach Name</th>
            <th>Position</th>
            <th>Status</th>
            <th>Availability</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($coachResult->num_rows > 0): ?>
            <?php while($coach = $coachResult->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($coach['full_name']); ?></td>
                <td><?= htmlspecialchars($coach['position']); ?></td>
                <td><?= htmlspecialchars($coach['status']); ?></td>
                <td>
                  <button class="toggle-btn <?= $coach['availability_count'] > 0 ? 'available' : 'not-available'; ?>" 
                          data-coach="<?= $coach['id']; ?>">
                    <?= $coach['availability_count'] > 0 ? 'Available' : 'Not Available'; ?>
                  </button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4">No coaches available.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Notifications -->
  <div class="card">
    <div class="employee-table-title">Notifications</div>
    <ul id="notification-list">
      <?php if ($notifResult->num_rows > 0): ?>
        <?php while($notif = $notifResult->fetch_assoc()): ?>
          <li>
            <b><?= htmlspecialchars($notif['member_name']) ?></b> - 
            <?= htmlspecialchars($notif['message']) ?> 
            <small>(<?= $notif['created_at'] ?>)</small>
          </li>
        <?php endwhile; ?>
      <?php else: ?>
        <li>No notifications.</li>
      <?php endif; ?>
    </ul> 
  </div>
</div>

<script>
// Toggle availability via AJAX
$('.toggle-btn').click(function() {
    let btn = $(this);
    let coach_id = btn.data('coach');

    $.post('toggle_availability.php', { coach_id: coach_id }, function(data) {
        if(data.status === 'available'){
            btn.text('Available').removeClass('not-available').addClass('available');
        } else {
            btn.text('Not Available').removeClass('available').addClass('not-available');
        }
    }, 'json');
});

// Notification auto-refresh
let lastNotifId = null;
function fetchNotifications() {
    $.getJSON('fetch_notifications.php', function(data) {
        let list = $("#notification-list");
        list.empty();

        if (!data || data.length === 0) {
            list.append("<li>No notifications.</li>");
            return;
        }

        data.forEach(function(notif, index) {
            list.append(`<li><b>${notif.member_name}</b> - ${notif.message} <small>(${notif.time})</small></li>`);

            if (index === 0 && (lastNotifId === null || notif.id !== lastNotifId)) {
                showPopup(notif.message);
                lastNotifId = notif.id;
            }
        });
    });
}

function showPopup(msg) {
    let popup = $('<div class="popup"></div>').text(msg);
    $('body').append(popup);
    popup.fadeIn(300).delay(3000).fadeOut(500, function(){ $(this).remove(); });
}

setInterval(fetchNotifications, 5000);
</script>
</body>
</html>
