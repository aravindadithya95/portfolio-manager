<?php
session_start();
if (!isset($_SESSION['username']))
{
    header("location: login.php");
}

require 'database.php';

$username = $_SESSION['username'];
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

#$p_cost_basis = 0;
#$p_mkt_value = 0;

fputcsv($output, array("Name","Symbol","Last price","Price in Foreign Currency","Change","Shares","Cost Basis","Market Value","Gain","Gain %","Expected Return"));
#fputcsv($output, $array("Cash", "Portfolio Cost Basis", "Portfolio Market Value"));
while ($record = mysqli_fetch_assoc($result))
{
$stockname =  "";
$stockname = $record['stock_name'];
$symbol = "";
$symbol = $record['symbol'];
$stockname = $symbol;
require 'scraper.php';
$category = "dow30";
$current_foreign_price = (int)$current_price;
if ($symbol == "TATAMOTORS.NS" || $symbol == "TCS.NS" || $symbol == "BHARTIARTL.NS" || $symbol == "KOTAKBANK.NS") {
$category = "overseas";
require 'currency.php';
$current_price /= $exc;
}
$finalprice = round($current_price, 2);
$fprice = "";
if ($category == "overseas")
{
$fprice = $current_foreign_price;
}
$sh = mysqli_fetch_assoc($result_sh);
$shares = $sh['sum(shares)'];
$cb = mysqli_fetch_assoc($result_cb);
$cost_basis = $cb['sum(cost_basis)'];
#$p_cost_basis = $p_cost_basis + $cost_basis;
$market_value = $shares * $current_price;
#$c = mysqli_fetch_assoc($result_cash);
#$cash = $c['cash'];
#$p_mkt_value = $p_mkt_value + $cash;
$mkt_val = round($market_value, 2);
$gain = $market_value - $cost_basis;
$gain_cent = ($gain/$cost_basis)*100;
$query_exp = "SELECT sept_price FROM stocks WHERE symbol = '$symbol'";
$result_exp = mysqli_query($conn, $query_exp);
$expr = mysqli_fetch_assoc($result_exp);
$sept_price = $expr['sept_price'];
$fraction=$current_price/$sept_price;
$rate=pow(($fraction), 48/365)-1;
$futureValue=$current_price*(pow((1+$rate),30/365));
$return=$shares*$futureValue;
$return = round($return, 2);

fputcsv($output, array($stockname,$symbol,$finalprice,$fprice,$gain_and_percent,$shares,$cost_basis,$mkt_val,$gain,$gain_cent, $return));
}
#fputcsv($output, $array($cash, $p_cost_basis, $p_mkt_value));
fclose($output);

?>
