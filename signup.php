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
	<h1>Sign Up</h1>
	<form action="scripts/register.php" method="post">
		<input type="text" name="name" placeholder="Name" />
		<br>
		<input type="text" name="username" placeholder="Username" />
		<br>
		<input type="password" name="password" placeholder="Password" />
		<br>
		<input type="password" name="re_password" placeholder="Re-enter password" />
		<br>
		<button type="submit" name="signup">Sign Up</button>
	</form>
</body>
</html>
