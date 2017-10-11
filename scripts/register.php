<?php
session_start();

if (!isset($_POST['signup'])) {
	header("location: ../signup.php");
	exit();
}

$name = $_POST['name'];
$username = $_POST['username'];
$password = $_POST['password'];
$re_password = $_POST['re_password'];

if ($password == $re_password) {
	require 'database.php';
	$query = "INSERT INTO users VALUES('$name', '$username', '$password', 0, 0, 0)";
	$result = mysqli_query($conn, $query);

	$_SESSION['username'] = $username;
	header("location: ../home.php");
} else {
	echo "Passwords don't match";
}
?>
