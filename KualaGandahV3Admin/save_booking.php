<?php
session_start();
include "conn.php";

if(!isset($_SESSION['user_id'])){
    die("User not logged in");
}

$uid = $_SESSION['user_id'];

$date = $_POST['booking_date'];
$adult = $_POST['adult'];
$child = $_POST['child'];
$total = $_POST['total'];

if(empty($date)){
    die("Please select booking date");
}

if(!is_numeric($adult) || !is_numeric($child)){
    die("Please enter numbers only");
}

if($adult < 0 || $child < 0){
    die("Ticket quantity cannot be negative");
}

if($adult == 0 && $child == 0){
    die("Please select at least 1 ticket");
}

if($date < date("Y-m-d")){
    die("Cannot book past date");
}

$category = trim($_POST['ticket_category'] ?? 'Standard');

$sql = "INSERT INTO bookings (user_id, booking_date, adult_qty, child_qty, total_price, ticket_category, status)
VALUES (?, ?, ?, ?, ?, ?, 'Pending')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isiiis", $uid, $date, $adult, $child, $total, $category);
$stmt->execute();

header("Location: history.php");
exit();
?>