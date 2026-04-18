<?php
include "../conn.php";
include "header_admin.php";

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: users.php');
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role <> 'admin'");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: users.php');
exit();
?>