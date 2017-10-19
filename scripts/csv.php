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

$query_cash = "SELECT cash FROM users where username = '$username'";
$result_cash = mysqli_query($conn, $query_cash);

header("Content-Type:text/csv; charset=utf-8");
header("Content-Disposition:attachment; filename=portfolio.csv");
$output = fopen("php://output", "w");

fputcsv($output, array("Name","Symbol","Last price","Price in Foreign Currency","Change","Shares","Cost Basis","Market Value","Gain","Gain %","Expected Return"));
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
$shares = "";
$shares = $record['shares'];
$cb = mysqli_fetch_assoc($result_cb);
$cost_basis = $cb['sum(cost_basis)'];
//$p_cost_basis = $p_cost_basis + $cost_basis;
$market_value = $shares * $current_price;
//$p_mkt_value = $p_mkt_value + $market_value;
$mkt_val = round($market_value, 2);
$gain = $market_value - $cost_basis;
$gain_cent = ($gain/$cost_basis)*100;

fputcsv($output, array($stockname,$symbol,$finalprice,$fprice,$gain_and_percent,$shares,$cost_basis,$mkt_val,$gain,$gain_cent));
}
fclose($output);

?>
