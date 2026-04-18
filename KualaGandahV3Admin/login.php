<?php
session_start();
include "conn.php";

$message="";
$type="";

if(isset($_POST['login'])){

$username=trim($_POST['username']);
$password=$_POST['password'];

if($username==""){
$message="Please enter your username";
$type="danger";
}

else if($password==""){
$message="Please enter your Password";
$type="danger";
}

else{

$sql="SELECT * FROM users WHERE username=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("s",$username);
$stmt->execute();

$result=$stmt->get_result();
$user=$result->fetch_assoc();

if($user && password_verify($password,$user['password'])){

$_SESSION['user_id']=$user['id'];
$_SESSION['fullname']=$user['fullname'];
$_SESSION['phone']=$user['phone'];
$_SESSION['role']=$user['role'];
$_SESSION['regdate']=$user['regdate'];

if($user['role']=="admin"){

header("Location: admin/dashboard.php");

}else{

header("Location: booking.php");

}

exit();

}else{

$message="Login failed. Invalid username or password.";
$type="danger";

}

}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Elephant Park Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

form{
position:relative;
width:100%;
max-width:380px;
padding:35px;
background:rgba(255,255,255,0.15);
backdrop-filter:blur(12px);
border-radius:16px;
box-shadow:0 10px 30px rgba(0,0,0,0.4);
border:1px solid rgba(255,255,255,0.2);
color:white;
z-index:2;
}

h3{
text-align:center;
margin-bottom:25px;
font-weight:600;
}

input.form-control{
background:#f5f5f5;
border:none;
margin-bottom:15px;
padding:12px;
}

button.btn-primary{
background:#2e7d32;
border:none;
color:white;
font-weight:600;
padding:12px;
}

button.btn-primary:hover{
background:#1b5e20;
}

a.btn-success{
background:rgba(255,255,255,0.2);
border:1px solid #d4af37;
color:#d4af37;
}

a.btn-success:hover{
background:#d4af37;
color:black;
}

.error{
text-align:center;
color:#ffcccc;
margin-bottom:10px;
}

</style>

</head>
<body>

<div class="overlay"></div>

<form method="POST">

<h3>Elephant Park Login</h3>

<?php if($message!=""){ ?>
<div class="alert alert-<?php echo $type; ?>">
<?php echo $message; ?>
</div>
<?php } ?>

<input class="form-control" name="username" placeholder="Username">

<input type="password" class="form-control" name="password" placeholder="Password">

<button class="btn btn-primary w-100" name="login">Login</button>

<a href="register.php" class="btn btn-success w-100 mt-2">No account? Register</a>

</form>

</body>
</html>