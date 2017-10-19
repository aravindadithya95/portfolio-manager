<?php
require 'database.php';
$stockname = $_SESSION['return_symbol'];
$url = 'https://finance.yahoo.com/quote/' . $stockname;

$data = file_get_contents($url);
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($data);
libxml_clear_errors();
$dom->saveHTML();

$xpath = new DOMXPath($dom);
$price = $xpath->query('//*[@id="quote-header-info"]/div[3]/div[1]/div/span[1]/text()');
$sign = $xpath->query('//*[@id="quote-header-info"]/div[3]/div[1]/div/span[2]/text()');
$change = $xpath->query('//*[@id="quote-header-info"]/div[3]/div[1]/div/span[2]/text()[2]');
$current_price = $price->item(0)->nodeValue;
$gain_and_percent = $sign->item(0)->nodeValue;// . $change->item(0)->nodeValue;
if (isset($change->item(0)->nodeValue)) {
    $gain_and_percent = (string)$gain_and_percent . $change->item(0)->nodeValue;
  }
#echo $current_price;
// Get session variables
$username = $_SESSION['username'];
$symbol = $_SESSION['return_symbol'];
$shares = $_SESSION['return_shares'];

// Check if user already owns that stock

  $query = "SELECT sept_price FROM stocks WHERE symbol = '$symbol'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $sept_price = $row['sept_price'];
#echo $sept_price;

$fraction=$current_price/$sept_price;
$rate=pow(($fraction), 48/365)-1;

#echo $rate;

$futureValue=$current_price*(pow((1+$rate),30/365));
$expectedReturn=$shares*$futureValue;
echo round($expectedReturn, 2);
?>
