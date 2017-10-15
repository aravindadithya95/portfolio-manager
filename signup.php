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
</head>
<body>
<center>
	<h1>Sign Up</h1>
	<form action="scripts/register.php" method="post">
		<input type="text" name="name" placeholder="Name" />
		<br><br>
		<input type="text" name="username" placeholder="Username" />
		<br><br>
		<input type="password" name="password" placeholder="Password" />
		<br><br>
		<input type="password" name="re_password" placeholder="Re-enter password" />
		<br><br>
		<button type="submit" name="signup">Sign Up</button>
	</form>
</center>
</body>
</html>
