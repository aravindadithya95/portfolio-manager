<?php
session_start();

$buy_stock = $_SESSION['buy_stock'] = $_POST['buy_stock'];
$buy_shares = $_SESSION['buy_shares'] = $_POST['buy_shares'];
$buy_price = $_SESSION['buy_price'] = $_POST['buy_price'];

$sell_stock = $_SESSION['sell_stock'] = $_POST['sell_stock'];
$sell_shares = $_SESSION['sell_shares'] = $_POST['sell_shares'];
$sell_price = $_SESSION['sell_price'] = $_POST['sell_price'];

$buy_category = "dow30";
$sell_category = "dow30";

if (isset($_POST['add'])) {
  $buy = false;
  $sell = false;
  if ($_POST['sell_shares'] != "" AND $_POST['sell_value'] != "" AND isset($_POST['radio'])) {
    $radio = $_POST['radio'];
    $sell = true;
    if ($sell_stock == "BHARTIARTL.NS" or $sell_stock == "TCS.NS" or $sell_stock == "KOTAKBANK.NS" or $sell_stock == "AXISBANK.NS")
      $sell_category = "overseas";
  }
  if ($_POST['buy_shares'] != "" AND $_POST['buy_value'] != "") {
    $buy = true;
    if ($buy_stock == "BHARTIARTL.NS" or $buy_stock == "TCS.NS" or $buy_stock == "KOTAKBANK.NS" or $buy_stock == "AXISBANK.NS")
      $buy_category = "overseas";
  }

  require 'db';

  $username = $_SESSION['username'];
  // Current portfolio value
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $cash = $row['cash'];
  $dow30_value = $row['dow30_value'];
  $overseas_value = $row['overseas_value'];

  $temp_cash = $cash;
  $temp_dow30_value = $dow30_value;
  $temp_overseas_value = $overseas_value;
  if ($buy) {
    $cost_basis = $buy_price * $buy_shares;
    if ($buy_category == "dow30") {
      $temp_dow30_value += $cost_basis;
    } else {
      $temp_overseas_value += $cost_basis;
    }
    $cash -= $cost_basis;
  }

  if ($sell) {
    $cost_basis = $sell_price * $sell_shares;
    if ($sell_category == "dow30") {
      $temp_dow30_value -= $cost_basis;
    } else {
      $temp_overseas_value -= $cost_basis;
    }
    $cash += $cost_basis;
  }

  $total_value = $temp_dow30_value + $temp_overseas_value;
  if ($temp_cash > 0.1 * $total_value) {
    echo "Too much cash";
    exit();
  }
  if ($overseas_value < 0.25 * $total_value OR $overseas_value > 0.35 * $total_value) {
    echo "70-30 imbalance";
    exit();
  }

  unset($_SESSION['buy_stock']);
  unset($_SESSION['buy_shares']);
  unset($_SESSION['buy_price']);
  unset($_SESSION['sell_stock']);
  unset($_SESSION['sell_shares']);
  unset($_SESSION['sell_price']);
  unset($_SESSION['select']);
  //header("location: ../home.php");
  exit();
}

if (isset($_POST['select'])) {
  $_SESSION['select'] = true;

  header("location: ../home.php");
  exit();
}

/*
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
*/
?>
