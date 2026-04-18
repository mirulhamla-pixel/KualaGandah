<?php
session_start();
include "conn.php";
include "header.php";

$uid = $_SESSION['user_id'] ?? 0;

$sql = "SELECT * FROM bookings WHERE user_id=? ORDER BY booking_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>

.container-box{
  background:
    linear-gradient(rgba(255,255,255,0.88),rgba(255,255,255,0.88)),
    url("image/gajah1.jpg");
  background-size:cover;
  background-position:center;
  background-repeat:no-repeat;
  width:min(100%, 980px);
  box-sizing:border-box;
  padding:24px;
  border-radius:15px;
  box-shadow:0 5px 15px rgba(0,0,0,0.1);
  margin:20px auto;
}

.container-box h3{
  font-size:1.65rem;
  margin-bottom:16px;
  color:#1b5e20;
  font-weight:700;
}

/* TABLE */

.table-responsive{
  overflow-x:auto;
  padding-bottom:8px;
}

.table-responsive table{
  width:100%;
  border-collapse:collapse;
  margin-top:20px;
  table-layout:auto;
  background:#fff;
  border-radius:12px;
  overflow:hidden;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  border:1px solid #e8e8e8;
}

.table-responsive th,
.table-responsive td{
  padding:12px 10px;
  vertical-align:middle;
  text-align:center;
}

th{
  background:#2e7d32;
  color:white;
  white-space:nowrap;
  font-weight:700;
  letter-spacing:0.02em;
  font-size:0.95rem;
}

td{
  border-bottom:1px solid #f0f0f0;
  word-wrap:break-word;
  word-break:break-word;
  font-size:0.92rem;
}

tr:nth-child(even) td{
  background:#fbfbfb;
}

tr:hover td{
  background:#f4f7f3;
}

/* BUTTON STYLE */

.btn{
  padding:7px 12px;
  border-radius:8px;
  font-size:13px;
  margin:2px;
  text-decoration:none;
  background:#6c757d;
  color:white;
  transition:all 0.25s ease;
  display:inline-block;
}

.btn:hover{
  background:#5a6268;
}

td input[type=file]{
  max-width:150px;
  width:100%;
  min-width:0;
  border:1px solid #ccc;
  border-radius:6px;
  padding:4px 6px;
}

.action-cell{
  display:flex;
  flex-direction:column;
  gap:10px;
  align-items:center;
}

.action-cell form{
  display:flex;
  flex-wrap:wrap;
  gap:10px;
  justify-content:center;
  align-items:center;
  width:100%;
  max-width:280px;
}

.action-cell div{
  display:flex;
  flex-wrap:wrap;
  gap:8px;
  justify-content:center;
}

@media (max-width: 1080px) {
  .table-responsive table{
    min-width:auto;
  }
  .table-responsive th,
  .table-responsive td{
    padding:10px 7px;
    font-size:0.88rem;
  }
  td input[type=file]{
    max-width:120px;
  }
}

</style>

<div class="container-box">

<h3>📋 Booking History</h3>

<div class="table-responsive">
<table>

<tr>
<th>Date</th>
<th>Category</th>
<th>Adult</th>
<th>Child</th>
<th>Total</th>
<th>Status</th>
<th>Receipt</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td><?php echo htmlspecialchars($row['booking_date']) ?></td>
<td><?php echo htmlspecialchars($row['ticket_category'] ?? 'Standard') ?></td>
<td><?php echo htmlspecialchars($row['adult_qty']) ?></td>
<td><?php echo htmlspecialchars($row['child_qty']) ?></td>
<td>RM<?php echo htmlspecialchars($row['total_price']) ?></td>
<td><?php echo htmlspecialchars($row['status'] ?? 'Pending') ?></td>
<td>
    <?php if(!empty($row['receipt_file'])){ ?>
        <a class="btn" href="uploads/<?php echo htmlspecialchars($row['receipt_file']) ?>" target="_blank">View</a>
    <?php } else { ?>
        <span class="btn" style="background:#6c757d;">No receipt</span>
    <?php } ?>
</td>
<td class="action-cell">
    <?php if($row['status'] === 'Pending'){ ?>
        <form method="POST" action="upload_receipt.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row['booking_id'] ?>">
            <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" required>
            <button class="btn" type="submit">Upload</button>
        </form>
    <?php } elseif($row['status'] === 'Paid'){ ?>
        <span class="btn" style="background:#ffc107; color:#000;">Awaiting confirmation</span>
    <?php } else { ?>
        <span class="btn" style="background:#28a745;">Confirmed</span>
    <?php } ?>
    <div>
        <a class="btn" href="edit_booking.php?id=<?php echo $row['booking_id']?>">Edit</a>
        <a class="btn" href="print_ticket.php?id=<?php echo $row['booking_id']?>">Print</a>
        <a class="btn" href="delete_booking.php?id=<?php echo $row['booking_id']?>" onclick="return confirm('Betul nak delete booking ni?');">Delete</a>
    </div>
</td>

</tr>

<?php } ?>

</table>

</div>

<?php include "footer.php"; ?>