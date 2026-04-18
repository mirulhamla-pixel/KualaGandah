<?php
session_start();
include "../conn.php";
include "header_admin.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="admin"){
header("Location: ../login.php");
exit();
}

/* TOTAL USERS */
$sql1="SELECT COUNT(*) as total_users FROM users";
$result1=$conn->query($sql1);
$row1=$result1->fetch_assoc();
$total_users=$row1['total_users'];

/* TOTAL BOOKINGS */
$sql2="SELECT COUNT(*) as total_bookings FROM bookings";
$result2=$conn->query($sql2);
$row2=$result2->fetch_assoc();
$total_bookings=$row2['total_bookings'];

/* BOOKING STATUS COUNTS */
$statusSql = "SELECT status, COUNT(*) AS count FROM bookings GROUP BY status";
$statusResult = $conn->query($statusSql);
$statusCounts = [
    'Pending' => 0,
    'Paid' => 0,
    'Confirmed' => 0
];
while ($statusRow = $statusResult->fetch_assoc()) {
    $statusCounts[$statusRow['status']] = intval($statusRow['count']);
}

/* TOTAL REVENUE */
$sql3="SELECT SUM(total_price) as revenue FROM bookings";
$result3=$conn->query($sql3);
$row3=$result3->fetch_assoc();
$revenue=$row3['revenue'] ?? 0;

/* LATEST BOOKINGS */
$sql4="
SELECT users.fullname, bookings.booking_date,
bookings.adult_qty, bookings.child_qty, bookings.total_price
FROM bookings
JOIN users ON bookings.user_id = users.id
ORDER BY bookings.created_at DESC
LIMIT 5
";

$latest=$conn->query($sql4);
?>

<style>

.dashboard{
max-width:1000px;
margin:auto;
}

.cards{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
margin-bottom:30px;
}

.card{
background:#f4f7f3;
padding:25px;
border-radius:12px;
text-align:center;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

.card h2{
color:#2e7d32;
}

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:10px;
border-bottom:1px solid #ddd;
text-align:left;
}

th{
background:#2e7d32;
color:white;
}

</style>

<div class="dashboard">

<h2>Admin Dashboard</h2>

<div class="cards">

<div class="card">
<h2><?php echo $total_users; ?></h2>
<p>Total Users</p>
</div>

<div class="card">
<h2><?php echo $total_bookings; ?></h2>
<p>Total Bookings</p>
</div>

<div class="card">
<h2>RM <?php echo $revenue; ?></h2>
<p>Total Revenue</p>
</div>

<div class="card">
<h2><?php echo $statusCounts['Pending']; ?></h2>
<p>Pending Bookings</p>
</div>

<div class="card">
<h2><?php echo $statusCounts['Paid']; ?></h2>
<p>Paid Bookings</p>
</div>

<div class="card">
<h2><?php echo $statusCounts['Confirmed']; ?></h2>
<p>Confirmed Bookings</p>
</div>

</div>

<h3>Latest Bookings</h3>

<table>

<tr>
<th>Name</th>
<th>Date</th>
<th>Adult</th>
<th>Child</th>
<th>Total</th>
</tr>

<?php while($row=$latest->fetch_assoc()){ ?>

<tr>

<td><?php echo $row['fullname']; ?></td>
<td><?php echo $row['booking_date']; ?></td>
<td><?php echo $row['adult_qty']; ?></td>
<td><?php echo $row['child_qty']; ?></td>
<td>RM <?php echo $row['total_price']; ?></td>

</tr>

<?php } ?>

</table>

</div>

<?php include "footer_admin.php"; ?>