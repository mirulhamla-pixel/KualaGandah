<?php
include "conn.php";

$id=$_GET['id'];

$sql="SELECT * FROM bookings WHERE booking_id=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();

$result=$stmt->get_result();
$row=$result->fetch_assoc();
?>

<style>

body{
font-family:Arial, sans-serif;
text-align:center;
background:#f4f7f3;
}

/* TICKET DESIGN */

.ticket{

background:
linear-gradient(rgba(255,255,255,0.55),rgba(255,255,255,0.55)),
url("image/gajahcomel.jpg");

background-size:cover;
background-position:center;
background-repeat:no-repeat;

border:3px dashed #2e7d32;
border-radius:15px;
padding:25px;
width:420px;
margin:auto;

box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

/* TITLE */

.ticket-title{
font-size:26px;
font-weight:bold;
color:#2e7d32;
margin-bottom:5px;
}

.ticket-sub{
color:#666;
margin-bottom:20px;
}

/* INFO */

.ticket-info{
text-align:left;
font-size:18px;
margin-top:15px;
}

.ticket-info p{
margin:8px 0;
}

/* TOTAL */

.total{
font-size:22px;
font-weight:bold;
margin-top:15px;
color:#2e7d32;
}

/* BUTTON */

button{

padding:10px 18px;
border:none;
border-radius:8px;
margin:10px;
cursor:pointer;
font-weight:500;

}

.print-btn{
background:#2e7d32;
color:white;
}

.print-btn:hover{
background:#1b5e20;
}

.history-btn{
background:#6c757d;
color:white;
}

.history-btn:hover{
background:#5a6268;
}

/* PRINT MODE */

@media print{

button{
display:none;
}

body{
background:white;
}

}

</style>

<div class="ticket">

<div class="ticket-title">
🐘 Kuala Gandah Elephant Sanctuary
</div>

<div class="ticket-sub">
Official Visitor Ticket
</div>

<hr>

<div class="ticket-info">

<p><strong>Booking Date:</strong> <?php echo $row['booking_date']; ?></p>

<p><strong>Adult Ticket:</strong> <?php echo $row['adult_qty']; ?></p>

<p><strong>Child Ticket:</strong> <?php echo $row['child_qty']; ?></p>

</div>

<hr>

<div class="total">
Total: RM<?php echo $row['total_price']; ?>
</div>

<p style="margin-top:15px">
Enjoy your visit! 🐘
</p>

</div>

<br>

<button class="print-btn" onclick="window.print()">
Print Ticket
</button>

<button class="history-btn" onclick="window.location.href='history.php'">
View Booking History
</button>