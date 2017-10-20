<?php
session_start();

if (isset($_SESSION['username'])) {
	header("location: home.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login | Portfolio</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
      .navbar{
        margin-bottom:0;
        border-radius:0;
      }
    </style>
</head>
<body>
<center>
	<form action="scripts/auth.php" method="post">
	<div class="container">
	
		  <center>
		  <h1>LOGIN</h1>
		<input type="text" name="username" placeholder="Username"/>
		<br><br>
		<input type="password" name="password" placeholder="Password"/>
		<br><br>
		<button type="submit" name="login" class="btn btn-default">Login</button>
	</center>
	
	</div>
	</form>
</center>
</body>
</html>
