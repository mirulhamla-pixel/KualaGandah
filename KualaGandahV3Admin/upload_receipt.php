<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: history.php');
    exit();
}

$stmt = $conn->prepare("SELECT user_id, status FROM bookings WHERE booking_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    die('Invalid booking.');
}

if ($booking['status'] !== 'Pending') {
    die('Receipt upload is only allowed for pending bookings.');
}

if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
    die('Please select a valid receipt file.');
}

$receipt = $_FILES['receipt'];
$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'application/pdf' => 'pdf'
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$fileType = finfo_file($finfo, $receipt['tmp_name']);
finfo_close($finfo);

if (!isset($allowedTypes[$fileType])) {
    die('Invalid file type. Allowed types: JPG, PNG, PDF.');
}

if ($receipt['size'] > 2 * 1024 * 1024) {
    die('File size must be 2MB or smaller.');
}

$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$extension = $allowedTypes[$fileType];
$filename = 'receipt_' . $id . '_' . time() . '.' . $extension;
$destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;

if (!move_uploaded_file($receipt['tmp_name'], $destination)) {
    die('Failed to save receipt file.');
}

$stmt = $conn->prepare("UPDATE bookings SET receipt_file = ?, status = 'Paid' WHERE booking_id = ? AND user_id = ?");
$stmt->bind_param("sii", $filename, $id, $_SESSION['user_id']);
$stmt->execute();

header('Location: history.php');
exit();
?>