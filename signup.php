<?php
session_start();

if (isset($_SESSION['username'])) {
	header("location: home.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign Up | Portfolio</title>
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
	<h1>Sign Up</h1>
	<form action="scripts/register.php" method="post">
	<div class="container">
		<input type="text" name="name" placeholder="Name" />
		<br><br>
		<input type="text" name="username" placeholder="Username" />
		<br><br>
		<input type="password" name="password" placeholder="Password" />
		<br><br>
		<input type="password" name="re_password" placeholder="Re-enter password" />
		<br><br>
		<button type="submit" name="signup" class="btn btn-default">Sign Up</button>
	</div>
	</form>
</center>
</body>
</html>
