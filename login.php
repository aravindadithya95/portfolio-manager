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
</head>
<body>
<center>
	<h1>Login</h1>
	<form action="scripts/auth.php" method="post">
		<input type="text" name="username" placeholder="Username"/>
		<br><br>
		<input type="password" name="password" placeholder="Password"/>
		<br><br>
		<button type="submit" name="login">Login</button>
	</form>
</center>
</body>
</html>
