<?php
require_once '../includes/session_check.php';
include '../db.php';

check_session(['employee']);

// Logged-in employee ID
$employee_id = $_SESSION['employee_id'] ?? 1;

// Fetch coaches and their class schedules
$coachQuery = "
    SELECT 
        e.id, 
        CONCAT(e.first_name, ' ', e.last_name) as full_name, 
        e.position, 
        e.status,
        (SELECT COUNT(DISTINCT day_of_week) 
         FROM fitness_classes fc 
         WHERE fc.trainer_id = e.id) AS availability_count
    FROM employees e
    WHERE e.status = 'Active' 
    AND (e.position = 'Coach' OR e.position = 'Trainer')
    ORDER BY e.first_name ASC, e.last_name ASC
";
$coachResult = $conn->query($coachQuery);

// Fetch notifications for this employee
$notifQuery = "
    SELECT n.id, n.message, n.created_at, 
           CONCAT(m.first_name, ' ', m.last_name) AS member_name, 
           CONCAT(e.first_name, ' ', e.last_name) AS employee_name
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

/* Schedule popup specific styles */
.schedule-popup {
    color: #fff;
}

.schedule-popup #coach-schedule::-webkit-scrollbar {
    width: 8px;
}

.schedule-popup #coach-schedule::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
    border-radius: 4px;
}

.schedule-popup #coach-schedule::-webkit-scrollbar-thumb {
    background: #00c4ff;
    border-radius: 4px;
}

.schedule-popup button:hover {
    background: #0099ff !important;
}

/* Overlay for popup */
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
}
</style>
</head>
<body>
<div class="sidebar">
  <div class="logo">NEXUS</div>
  <ul class="nav-menu">
    <li><a href="employee-dashboard.php"><img src="../images/icons/dashboard-home-icon.svg" class="nav-icon"> Dashboard</a></li>
    <li><a href="employee-members.php"><img src="../images/icons/dashboard-members-icon.svg" class="nav-icon"> Members</a></li>
    <li><a href="employee-add-member.php"><img src="../images/icons/dashboard-profile-icon.svg" alt="Add Member" class="nav-icon"> Add Member</a></li>
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
                    <?= $coach['availability_count'] > 0 ? 'View Schedule (' . $coach['availability_count'] . ' days)' : 'No Classes Scheduled'; ?>
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
// Show coach schedule when clicking the availability button
$('.toggle-btn').click(function() {
    let btn = $(this);
    let coach_id = btn.data('coach');
    let coach_name = btn.closest('tr').find('td:first').text();

    // Remove any existing popups
    $('.schedule-popup').remove();

    // Create a popup to show the coach's schedule
    let popup = $('<div class="schedule-popup" style="width: 300px; background: #1e2a38; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.5); position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; border-radius: 8px;">' +
        '<h3 style="margin-top: 0; color: #fff; margin-bottom: 15px;">' + coach_name + '\'s Schedule</h3>' +
        '<div id="coach-schedule" style="max-height: 300px; overflow-y: auto;"></div>' +
        '<button onclick="$(\'.schedule-popup\').remove()" style="width: 100%; margin-top: 15px; padding: 8px; background: #00c4ff; border: none; color: white; border-radius: 4px; cursor: pointer;">Close</button>' +
        '</div>');

    $('body').append(popup);

    // Add loading indicator
    $('#coach-schedule').html('<p style="color: #fff; text-align: center;">Loading schedule...</p>');

    // Fetch and display the coach's schedule
    $.ajax({
        url: 'get_coach_schedule.php',
        data: { coach_id: coach_id },
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            let scheduleHtml = '';
            if (data.schedule && data.schedule.length > 0) {
                // Sort days of the week
                const dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                data.schedule.sort((a, b) => dayOrder.indexOf(a.day) - dayOrder.indexOf(b.day));
                
                data.schedule.forEach(function(class_info) {
                    scheduleHtml += '<div style="margin-bottom: 15px; color: #fff; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 4px;">' +
                        '<div style="font-weight: bold; color: #00c4ff; margin-bottom: 5px;">' + class_info.day + '</div>' +
                        '<div>' + class_info.time + '</div>' +
                        '<div style="color: #8a94a6;">' + class_info.class_name + '</div>' +
                        '</div>';
                });
            } else {
                scheduleHtml = '<p style="color: #fff; text-align: center;">No classes scheduled</p>';
            }
            $('#coach-schedule').html(scheduleHtml);
        },
        error: function(xhr, status, error) {
            $('#coach-schedule').html('<p style="color: #ff4444; text-align: center;">Error loading schedule: ' + error + '</p>');
            console.error('Error fetching schedule:', error);
        }
    });

    // Close popup when clicking outside
    $(document).mouseup(function(e) {
        var container = $('.schedule-popup');
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.remove();
        }
    });
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
