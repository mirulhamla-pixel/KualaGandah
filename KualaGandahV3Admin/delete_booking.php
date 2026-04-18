<?php
session_start();
include "conn.php";
include "header.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id = intval($_GET['id'] ?? 0);

$sql = "DELETE FROM bookings WHERE booking_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();
?>

<style>

.container-box{

background:
linear-gradient(rgba(255,255,255,0.7),rgba(255,255,255,0.7)),
url("image/gajahh.jpg");

background-size:cover;
background-position:center;
background-repeat:no-repeat;

padding:40px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,0.15);
max-width:500px;
margin:auto;
text-align:center;

}

.message{
font-size:20px;
color:#2e7d32;
font-weight:bold;
}

</style>

<div class="container-box">

<div class="message">
Booking Deleted Successfully
</div>

<p>Redirecting to booking history...</p>

</div>

<script>

setTimeout(function(){
window.location.href="history.php";
},2000);

</script>

<?php include "footer.php"; ?>