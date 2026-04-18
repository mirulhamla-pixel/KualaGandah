<?php
include "conn.php";

$message="";
$type="";

if(isset($_POST['register'])){

$fullname=trim($_POST['fullname']);
$username=trim($_POST['username']);
$password=$_POST['password'];
$phone=trim($_POST['phone']);
$date=date("Y-m-d");

if($fullname==""){
$message="Please enter your fullname";
$type="danger";
}

else if($username==""){
$message="Please enter your username";
$type="danger";
}

else if(strlen($password)<6){
$message="Password must be at least 6 characters";
$type="danger";
}

else if($phone==""){
$message="Please enter your Phone number";
$type="danger";
}

else if(!is_numeric($phone)){
$message="Phone must be numbers only";
$type="danger";
}

else{

    $checkSql = "SELECT id FROM users WHERE username = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $message = "That username is already taken.";
        $type = "danger";
    } else {
        $hash = password_hash($password,PASSWORD_DEFAULT);

        $sql="INSERT INTO users(fullname,username,password,phone,regdate)
VALUES(?,?,?,?,?)";

        $stmt=$conn->prepare($sql);
        $stmt->bind_param("sssss",$fullname,$username,$hash,$phone,$date);
        $stmt->execute();

        $message="Register success. Please login.";
        $type="success";
    }
}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Kuala Gandah Ticket System</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

body{
margin:0;
height:100vh;
font-family:'Poppins',sans-serif;
background:url("image/gajahjalan.jpg") no-repeat center center/cover;
display:flex;
align-items:center;
justify-content:center;
}

.overlay{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(27,46,32,0.75);
}

.container-box{
position:relative;
background:rgba(255,255,255,0.15);
backdrop-filter:blur(12px);
padding:35px;
width:380px;
border-radius:16px;
box-shadow:0 10px 30px rgba(0,0,0,0.4);
border:1px solid rgba(255,255,255,0.2);
color:white;
z-index:2;
text-align:center;
}

h3{
margin-bottom:25px;
font-weight:600;
}

input.form-control{
background:#f5f5f5;
border:none;
margin-bottom:15px;
padding:12px;
}

button.btn-success{
background:#2e7d32;
border:none;
font-weight:600;
padding:12px;
}

button.btn-success:hover{
background:#1b5e20;
}

a.btn-primary{
background:rgba(255,255,255,0.2);
border:1px solid #d4af37;
color:#d4af37;
}

a.btn-primary:hover{
background:#d4af37;
color:black;
}

.alert-success{
background:#d1e7dd;
color:#0f5132;
border:none;
}

</style>

</head>

<body>

<div class="overlay"></div>

<div class="container-box">

<h3>Register Account</h3>

<?php if($message!=""){ ?>
<div class="alert alert-<?php echo $type; ?>">
<?php echo $message; ?>
</div>
<?php } ?>

<form method="POST">

<input class="form-control" name="fullname" placeholder="Fullname">

<input class="form-control" name="username" placeholder="Username">

<input type="password" class="form-control" name="password" placeholder="Password">

<input class="form-control" name="phone" placeholder="Phone">

<button class="btn btn-success w-100" name="register">Register</button>

<a href="login.php" class="btn btn-primary w-100 mt-2">
Already have account? Login
</a>

</form>

</div>

</body>
</html>