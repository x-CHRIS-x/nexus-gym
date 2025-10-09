<?php
// Start session only if not started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔒 Strong cache control (prevents Chrome & mouse back button from showing cached pages)
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// ✅ Function to check session and role access
function check_session($allowed_roles = []) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: ../login.php");
        exit();
    }

    // Ensure $allowed_roles is always an array
    if (!is_array($allowed_roles)) {
        $allowed_roles = [$allowed_roles];
    }

    // Restrict access if user’s role not allowed
    if (!empty($allowed_roles) && !in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: ../login.php");
        exit();
    }
}
?>
