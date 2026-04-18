<?php
session_start();
include "conn.php";
include "header.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$error = "";
$showConfirm = false;

$fullname = $_SESSION['fullname'] ?? "";
$phone = $_SESSION['phone'] ?? "";
$role = $_SESSION['role'] ?? "";
$regdate = $_SESSION['regdate'] ?? "";

$date = $_SESSION['date'] ?? "";
$adult = $_SESSION['adult'] ?? 0;
$child = $_SESSION['child'] ?? 0;
$ticket_category = $_SESSION['ticket_category'] ?? "Standard";

if(isset($_POST['submit'])){

    $date = $_POST['booking_date'] ?? "";
    $adult_raw = $_POST['adult'] ?? "";
    $child_raw = $_POST['child'] ?? "";
    $category = trim($_POST['ticket_category'] ?? "Standard");

    // VALIDATION
    if(!is_numeric($adult_raw) || !is_numeric($child_raw)){
        $error = "Please enter numbers only";
    }
    else{

        $adult = intval($adult_raw);
        $child = intval($child_raw);

        if(empty($date)){
            $error = "Please select booking date";
        }
        elseif(empty($category)){
            $error = "Please select a ticket category";
        }
        elseif($adult == 0 && $child == 0){
            $error = "Please enter ticket quantity";
        }
        elseif($adult < 0 || $child < 0){
            $error = "Ticket cannot be negative";
        }
        elseif($date < date("Y-m-d")){
            $error = "Cannot book past date";
        }
        else {
            $_SESSION['date'] = $date;
            $_SESSION['adult'] = $adult;
            $_SESSION['child'] = $child;
            $_SESSION['ticket_category'] = $category;
            $ticket_category = $category;
            $showConfirm = true;
        }
    }
}

if(isset($_POST['confirm'])){

    $uid = $_SESSION['user_id'];
    $date = $_SESSION['date'] ?? "";
    $adult = $_SESSION['adult'] ?? 0;
    $child = $_SESSION['child'] ?? 0;
    $ticket_category = $_SESSION['ticket_category'] ?? "Standard";

    if(empty($date) || ($adult == 0 && $child == 0)){
        $error = "Booking invalid. Please proceed again.";
    }
    else{

        $total = ($adult*30)+($child*15);

        $sql = "INSERT INTO bookings (user_id, booking_date, adult_qty, child_qty, total_price, ticket_category, status)
                VALUES (?,?,?,?,?,?, 'Pending')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isiiis", $uid, $date, $adult, $child, $total, $ticket_category);
        $stmt->execute();

        unset($_SESSION['date']);
        unset($_SESSION['adult']);
        unset($_SESSION['child']);
        unset($_SESSION['ticket_category']);

        header("Location: history.php");
        exit();
    }
}
?>

<style>

.container-box{
background:
linear-gradient(rgba(255,255,255,0.75),rgba(255,255,255,0.75)),
url("image/gajahh.jpg");

background-size:cover;
background-position:center;
background-repeat:no-repeat;

padding:30px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
max-width:600px;
margin:auto;
}

.user-info{
background:#f4f7f3;
padding:15px;
border-radius:10px;
margin-bottom:20px;
}

input{
width:100%;
padding:10px;
border-radius:8px;
border:1px solid #ccc;
margin-bottom:15px;
}

button{
background:#2e7d32;
color:white;
border:none;
padding:10px 18px;
border-radius:8px;
font-weight:500;
}

button:hover{
background:#1b5e20;
}

.confirm-section{
margin-top:25px;
background:#f4f7f3;
padding:20px;
border-radius:12px;
border-left:5px solid #2e7d32;
}

/* ERROR BOX */
.error-box{
background:#ffebee;
color:#c62828;
padding:12px;
border-radius:8px;
margin-bottom:15px;
font-weight:500;
border-left:5px solid #c62828;
}

</style>

<div class="container-box">

<h3>Booking Form</h3>

<div class="user-info">
<p><strong>Name:</strong> <?php echo $fullname; ?></p>
<p><strong>Phone:</strong> <?php echo $phone; ?></p>
<p><strong>Role:</strong> <?php echo $role; ?></p>
<p><strong>Registration Date:</strong> <?php echo $regdate; ?></p>
</div>

<?php if($error!=""){ ?>
<div class="error-box">
<?php echo $error; ?>
</div>
<?php } ?>

<!-- penting: novalidate -->
<form method="POST" novalidate>

<label>Booking Date</label>
<input type="date" name="booking_date" value="<?php echo $date; ?>">

<label>Ticket Category</label>
<select name="ticket_category" class="form-control">
    <option value="Standard" <?php echo $ticket_category === 'Standard' ? 'selected' : ''; ?>>Standard</option>
    <option value="Family" <?php echo $ticket_category === 'Family' ? 'selected' : ''; ?>>Family</option>
    <option value="Child" <?php echo $ticket_category === 'Child' ? 'selected' : ''; ?>>Child</option>
    <option value="Combo" <?php echo $ticket_category === 'Combo' ? 'selected' : ''; ?>>Combo</option>
</select>

<label>Adult Ticket (RM30)</label>
<input type="text" name="adult" value="<?php echo $adult; ?>">

<label>Child Ticket (RM15)</label>
<input type="text" name="child" value="<?php echo $child; ?>">

<button type="submit" name="submit">Proceed Booking</button>

</form>

<?php if($showConfirm): ?>

<div class="confirm-section">

<h3>Confirm Booking</h3>

<p><strong>Name:</strong> <?php echo $fullname; ?></p>
<p><strong>Phone:</strong> <?php echo $phone; ?></p>
<p><strong>Role:</strong> <?php echo $role; ?></p>

<p><strong>Booking Date:</strong> <?php echo $date; ?></p>
<p><strong>Ticket Category:</strong> <?php echo htmlspecialchars($ticket_category); ?></p>
<p><strong>Adult:</strong> <?php echo $adult; ?></p>
<p><strong>Child:</strong> <?php echo $child; ?></p>

<p><strong>Total:</strong> RM<?php echo ($adult*30)+($child*15); ?></p>

<form method="POST">
<button type="submit" name="confirm">Confirm Booking</button>
</form>

</div>

<?php endif; ?>

</div>

<?php include "footer.php"; ?>