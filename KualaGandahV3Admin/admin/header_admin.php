<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_REQUEST['ajax'])) {
    return;
}

$page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Panel - Kuala Gandah</title>

<link rel="stylesheet" href="../style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>

/* NAVBAR */

.navbar{
background:#1b5e20;
}

/* MENU */

.nav-link{
color:white !important;
margin-right:15px;
transition:0.3s;
}

.nav-link:hover{
color:#c8e6c9 !important;
}

/* ACTIVE PAGE */

.nav-link.active{
color:#a5d6a7 !important;
font-weight:600;
}

/* BRAND */

.navbar-brand{
font-weight:bold;
color:#ffd54f !important;
font-size:22px;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand" href="dashboard.php">
Admin Panel
</a>

<ul class="navbar-nav me-auto">

<li class="nav-item">
<a class="nav-link <?php if($page=='dashboard.php') echo 'active'; ?>" href="dashboard.php">
Dashboard
</a>
</li>

<li class="nav-item">
<a class="nav-link <?php if($page=='users.php') echo 'active'; ?>" href="users.php">
Users
</a>
</li>

<li class="nav-item">
<a class="nav-link <?php if($page=='bookings.php') echo 'active'; ?>" href="bookings.php">
Bookings
</a>
</li>

<li class="nav-item">
<a class="nav-link <?php if($page=='reports.php') echo 'active'; ?>" href="reports.php">
Reports
</a>
</li>

</ul>

<ul class="navbar-nav">

<li class="nav-item">
<span class="nav-link">
Admin: <?php echo $_SESSION['fullname'] ?? "Admin"; ?>
</span>
</li>

<li class="nav-item">
<a class="nav-link text-warning" href="../login.php">
Logout
</a>
</li>

</ul>

</div>
</nav>

<div class="container mt-4">