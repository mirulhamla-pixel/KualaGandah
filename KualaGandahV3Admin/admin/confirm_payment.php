<?php
include "../conn.php";
include "header_admin.php";

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: bookings.php');
    exit();
}

$stmt = $conn->prepare("UPDATE bookings SET status = 'Confirmed' WHERE booking_id = ? AND status = 'Paid'");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: bookings.php');
exit();
?>