<?php

$host="localhost";
$user="root";
$pass="";
$db="kuala_gandah";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add booking support fields if missing (receipt upload, status, category)
$conn->query("ALTER TABLE bookings ADD COLUMN IF NOT EXISTS status VARCHAR(20) NOT NULL DEFAULT 'Pending'");
$conn->query("ALTER TABLE bookings ADD COLUMN IF NOT EXISTS receipt_file VARCHAR(255) DEFAULT NULL");
$conn->query("ALTER TABLE bookings ADD COLUMN IF NOT EXISTS ticket_category VARCHAR(50) NOT NULL DEFAULT 'General'");

?>