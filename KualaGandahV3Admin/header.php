<?php
if(session_status()==PHP_SESSION_NONE){
session_start();
}

$page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>

<title>Kuala Gandah Ticket System</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>

/* NAVBAR THEME HIJAU */

.navbar{
background:#2e7d32;
}

/* MENU STYLE */

.nav-link{
color:white !important;
margin-right:15px;
transition:0.3s;
}

/* HOVER EFFECT */

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

<a class="navbar-brand" href="booking.php">
Kuala Gandah Ticket
</a>

<ul class="navbar-nav me-auto">

<li class="nav-item">
<a class="nav-link <?php if($page=='booking.php') echo 'active'; ?>" href="booking.php">Booking</a>
</li>

<li class="nav-item">
<a class="nav-link <?php if($page=='history.php') echo 'active'; ?>" href="history.php">History</a>
</li>

</ul>

<ul class="navbar-nav">

<li class="nav-item">
<span class="nav-link">
<?php echo $_SESSION['fullname'] ?? "Guest"; ?>
</span>
</li>

<li class="nav-item">
<a class="nav-link text-warning" href="login.php">Logout</a>
</li>

</ul>

</div>
</nav>

<div class="container mt-4">