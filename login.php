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
	<h1>Login</h1>
	<form action="scripts/auth.php" method="post">
		<input type="text" name="username" placeholder="Username"/>
		<br>
		<input type="password" name="password" placeholder="Password"/>
		<br>
		<button type="submit" name="login">Login</button>
	</form>
</body>
</html>
