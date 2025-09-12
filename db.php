<?php
$conn = new mysqli("localhost", "root", "", "nexus_gym");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>