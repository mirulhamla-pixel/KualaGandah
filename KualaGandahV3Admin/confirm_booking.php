<?php
session_start();
include "header.php";

$date=$_POST['booking_date'];
$adult=$_POST['adult'];
$child=$_POST['child'];

if($date < date("Y-m-d")){
die("Cannot book past date");
}

function calculateTotal($adult,$child){

$price=[
"adult"=>30,
"child"=>15
];

return ($adult*$price['adult'])+($child*$price['child']);

}

$total=calculateTotal($adult,$child);
?>

<style>

.container-box{
background:white;
padding:30px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
max-width:600px;
margin:auto;
text-align:center;
}

.subtitle{
color:#2e7d32;
margin-bottom:20px;
font-weight:500;
}

.booking-summary{
background:#f4f7f3;
padding:20px;
border-radius:12px;
margin-top:20px;
margin-bottom:20px;
text-align:left;
border-left:5px solid #2e7d32;
}

.booking-summary p{
margin:8px 0;
font-size:16px;
}

button{
background:#2e7d32;
border:none;
padding:12px;
font-weight:500;
border-radius:8px;
}

button:hover{
background:#1b5e20;
}

</style>

<div class="container-box">

<h2>🐘 Kuala Gandah Ticket Booking</h2>
<p class="subtitle">Elephant Conservation Centre</p>

<h3>Confirm Booking</h3>

<div class="booking-summary">

<p><strong>Date:</strong> <?php echo $date ?></p>
<p><strong>Adult:</strong> <?php echo $adult ?></p>
<p><strong>Child:</strong> <?php echo $child ?></p>
<p><strong>Total Price:</strong> RM <?php echo $total ?></p>

</div>

<form method="POST" action="save_booking.php">

<input type="hidden" name="date" value="<?php echo $date ?>">
<input type="hidden" name="adult" value="<?php echo $adult ?>">
<input type="hidden" name="child" value="<?php echo $child ?>">
<input type="hidden" name="total" value="<?php echo $total ?>">

<button class="btn btn-success w-100">Confirm Booking</button>

</form>

</div>

<?php include "footer.php"; ?>