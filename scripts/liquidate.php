<?php
session_start();

if (!isset($_SESSION['username'])) {
  header('location: ../login.php');
  exit;
}

if (!isset($_POST['liquidate'])) {
  header('location: ../login.php');
  exit;
}

$username = $_SESSION['username'];
require 'database.php';

$sum = 0;

$query = "SELECT * FROM user_stocks WHERE username = '$username'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
  $shares = $row['shares'];
  $symbol = $row['symbol'];

  $query = "SELECT * FROM stocks WHERE symbol = '$symbol'";
  $result2 = mysqli_query($conn, $query);
  $row2 = mysqli_fetch_assoc($result2);

  $price = $row2['price'];
  $price_overseas = $row2['price_overseas'];

  $total = $shares * $price;

  $sum += $total;

  $query = "INSERT INTO transactions VALUES(
    NULL,
    'Sell',
    '$username',
    '$symbol',
    now(),
    '$shares',
    '$price',
    '$price_overseas',
    '$total'
  )";
  mysqli_query($conn, $query);
}

$query = "SELECT cash FROM users WHERE username = '$username'";
mysqli_query($conn, $query);
$cash = mysqli_fetch_assoc($query);

$sum += $cash;

$query = "DELETE FROM user_stocks WHERE username = '$username'";
mysqli_query($conn, $query);

$query = "UPDATE users SET cash = 0, dow30_value = 0, overseas_value = 0 WHERE username ='$username'";
mysqli_query($conn, $query);

$query = "INSERT INTO transactions(type, username, cash_value) VALUES('Withdraw', '$username', '$sum')";

$_SESSION['flash'] = array("Portfolio successfully liquidated for $" . $sum, success);

header('location: ../home.php');
exit;
?>
