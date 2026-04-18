<?php
session_start();
include "conn.php";
include "header.php";

$id = $_POST['id'];
$date = $_POST['booking_date'];
$adult = $_POST['adult'];
$child = $_POST['child'];
$ticket_category = trim($_POST['ticket_category'] ?? 'Standard');

function calculateTotal($adult, $child) {
    $price = [
        "adult" => 30,
        "child" => 15
    ];

    return ($adult * $price['adult']) + ($child * $price['child']);
}

$total = calculateTotal($adult, $child);

$sql = "UPDATE bookings 
SET booking_date = ?, adult_qty = ?, child_qty = ?, total_price = ?, ticket_category = ?
WHERE booking_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siiisi", $date, $adult, $child, $total, $ticket_category, $id);
$stmt->execute();

header("Location: history.php");