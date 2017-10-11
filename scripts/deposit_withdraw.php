<?php
session_start();

if (!isset($_POST['add'])) {
  header("location: ../home.php");
  exit();
}

$username = $_SESSION['username'];
$amount = $_POST['amount'];

require 'database.php';

if ($_POST['type'] == "deposit") {
  $query = "UPDATE users SET cash = cash + '$amount' WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  $query = "INSERT INTO transactions VALUES(\"Deposit Cash\", '$username', \"\", now(), 0, 0, '$amount', 0)";
  $result = mysqli_query($conn, $query);
} else {
  $query = "UPDATE users SET cash = cash - '$amount' WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  $query = "INSERT INTO transactions VALUES(\"Withdraw Cash\", '$username', \"\", now(), 0, 0, '$amount', 0)";
  $result = mysqli_query($conn, $query);
}

header("location: ../home.php");
?>
