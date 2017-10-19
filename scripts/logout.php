<?php
session_start();
if (isset($_GET['logout']) or isset($_SESSION['logout'])) {
	session_destroy();
	header("location: ../login.php");
} else {
	header("location: ../home.php");
}
?>
