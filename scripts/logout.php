<?php
session_start();
if (isset($_GET['logout'])) {
	session_destroy();
	header("location: ../login.php");
} else {
	header("location: ../home.php");
}
?>
