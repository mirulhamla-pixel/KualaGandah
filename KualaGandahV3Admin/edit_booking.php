<?php
session_start();
include "conn.php";
include "header.php";

$id = $_GET['id'];

$sql="SELECT * FROM bookings WHERE booking_id=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();
$result=$stmt->get_result();
$row=$result->fetch_assoc();

/* CHECK CONSTRAINT */

$today = new DateTime();
$booking_date = new DateTime($row['booking_date']);

$diff = $today->diff($booking_date)->days;

if($booking_date <= $today || $diff < 1){
die("Booking cannot be edited within 1 day before booking date");
}

?>

<style>

/* CONTAINER DESIGN */

.container-box{

background:
linear-gradient(rgba(255,255,255,0.75),rgba(255,255,255,0.75)),
url("image/gajahhh.jpg");

background-size:cover;
background-position:center;
background-repeat:no-repeat;

padding:30px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
max-width:600px;
margin:auto;

}

/* TITLE */

.subtitle{
color:#2e7d32;
margin-bottom:20px;
font-weight:500;
}

/* INPUT */

input[type=date],
input[type=number]{

width:100%;
padding:10px;
border-radius:8px;
border:1px solid #ccc;
margin-bottom:15px;

}

/* BUTTON */

button{

background:#2e7d32;
color:white;
border:none;
padding:12px;
border-radius:8px;
font-weight:500;
transition:0.3s;

}

button:hover{

background:#1b5e20;

}

</style>

<div class="container-box">

<h2>🐘 Edit Booking</h2>
<p class="subtitle">Update your Kuala Gandah ticket</p>

<form method="POST" action="update_booking.php">

<input type="hidden" name="id" value="<?php echo $row['booking_id']; ?>">

<label>Booking Date</label>
<input type="date" name="booking_date"
value="<?php echo $row['booking_date']; ?>" required>

<label>Ticket Category</label>
<select name="ticket_category" class="form-control mb-3">
    <option value="Standard" <?php echo $row['ticket_category'] === 'Standard' ? 'selected' : ''; ?>>Standard</option>
    <option value="Family" <?php echo $row['ticket_category'] === 'Family' ? 'selected' : ''; ?>>Family</option>
    <option value="Child" <?php echo $row['ticket_category'] === 'Child' ? 'selected' : ''; ?>>Child</option>
    <option value="Combo" <?php echo $row['ticket_category'] === 'Combo' ? 'selected' : ''; ?>>Combo</option>
</select>

<label>Adult Ticket</label>
<input type="number" name="adult"
value="<?php echo $row['adult_qty']; ?>" min="0">

<label>Child Ticket</label>
<input type="number" name="child"
value="<?php echo $row['child_qty']; ?>" min="0">

<button type="submit" class="btn btn-success w-100">
Update Booking
</button>

</form>

</div>

<?php include "footer.php"; ?>