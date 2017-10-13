<?php
session_start();

$_SESSION['buy_stock'] = $_POST['buy_stock'];
$_SESSION['buy_shares'] = $_POST['buy_shares'];
$_SESSION['buy_price'] = $_POST['buy_price'];

$_SESSION['sell_stock'] = $_POST['sell_stock'];

if (isset($_POST['add'])) {
  

  //header("location: ../home.php");
  exit();
}

if (isset($_POST['select'])) {
  $_SESSION['select'] = true;

  header("location: ../home.php");
  exit();
}

if (!isset($_POST['add'])) {
  header("location: ../home.php");
  exit();
}

$_SESSION['stock'] = $_POST['stock'];
$_SESSION['shares'] = $_POST['shares'];
$_SESSION['price'] = 100;

if ($_POST['type'] == "buy") {
  $_SESSION['buy'] = true;
  header("location: buy_stock.php");
} else {
  $_SESSION['sell'] = true;
  header("location: sell_stock.php");
}
?>
