<?php
session_start();

if (!isset($_SESSION['username']))
{
    header('location: ../login.php');
}

$username = $_SESSION['username'];

require 'database.php';

$query = "SELECT * FROM user_stocks, stocks WHERE username = '$username' and user_stocks.symbol = stocks.symbol group by user_stocks.symbol";
$result = mysqli_query($conn, $query);

$query_cb = "SELECT sum(cost_basis) FROM user_stocks WHERE username = '$username' GROUP BY symbol";
$result_cb = mysqli_query($conn, $query_cb);

$query_sh = "SELECT sum(shares) FROM user_stocks WHERE username = '$username' GROUP BY symbol";
$result_sh = mysqli_query($conn, $query_sh);

$query_cash = "SELECT cash FROM users where username = '$username'";
$result_cash = mysqli_query($conn, $query_cash);

header("Content-Type:text/csv; charset=utf-8");
header("Content-Disposition:attachment; filename=portfolio.csv");
$output = fopen("php://output", "w");

fputcsv($output, array("Name", "Symbol", "Last price", "Price in Foreign Currency", "Change", "Shares", "Cost Basis", "Market Value", "Gain", "Gain %", "Expected Return"));
while ($row = mysqli_fetch_assoc($result)) {
  $stockname = $row['stock_name'];
  $symbol = $row['symbol'];
  $price = $row['price'];
  $price_overseas = $row['price_overseas'];
  $price_change = $row['price_change'];

  $row_sh = mysqli_fetch_assoc($result_sh);
  $shares = $row_sh['sum(shares)'];

  $row_cb = mysqli_fetch_assoc($result_cb);
  $cost_basis = $row_cb['sum(cost_basis)'];

  $market_value = $shares * $price;
  $gain = $market_value - $cost_basis;
  $gain_percent = $gain / $cost_basis * 100;

  // Expected Return
  $query_exp = "SELECT sept_price FROM stocks WHERE symbol = '$symbol'";
  $result_exp = mysqli_query($conn, $query_exp);
  $expr = mysqli_fetch_assoc($result_exp);
  $sept_price = $expr['sept_price'];
  $fraction=$price/$sept_price;
  $rate=pow(($fraction), 48/365)-1;
  $futureValue=$price*(pow((1+$rate),30/365));
  $return=$shares*$futureValue;
  $return = round($return, 2);

  fputcsv($output, array($stockname, $symbol, $price, $price_overseas, $price_change, $shares, $cost_basis, $market_value, $gain, $gain_percent, $return));
}

fclose($output);
?>
