<?php
require_once 'database.php';
require 'currency_inr.php';

function update_db($symbol, $price, $price_overseas, $change) {
  global $conn;

  $query = "UPDATE stocks SET price = '$price', price_overseas = '$price_overseas', price_change = '$change' WHERE symbol = '$symbol'";
  mysqli_query($conn, $query);
}

function scrape($symbol) {
  $url = 'https://finance.yahoo.com/quote/' . $symbol;

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
  # $stock_beta = $xpath->query('//*[@id="quote-summary"]/div[2]/table/tbody/tr[2]/td[2]/span/text()');

  $current_price = $price->item(0)->nodeValue;
  $gain_and_percent = $sign->item(0)->nodeValue;// . $change->item(0)->nodeValue;
  if (isset($change->item(0)->nodeValue)) {
      $gain_and_percent = (string)$gain_and_percent . $change->item(0)->nodeValue;
  }
  $current_price = str_replace(",", "", $current_price);
  # $beta = $stock_beta->item(0)->nodeValue;

  # echo $current_price . "<br>";
  # echo $gain_and_percent . "<br>";
  # echo $beta;

  return array($current_price, $gain_and_percent);
}

$query = "SELECT * FROM stocks";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
  $symbol = $row['symbol'];
  $category = $row['category'];

  $results = scrape($symbol);

  $price = $results[0];
  $change = $results[1];

  $price_overseas = 0;
  if ($category == 'BSE 30') {
    $price_overseas = $price;
    $price /= $exc;
  }

  $price = (float) round($price, 2);
  $price_overseas = (float) round($price_overseas, 2);

  echo $symbol . " " . $price . " " . $price_overseas . " " . $change;
  echo "<br>";

  update_db($symbol, $price, $price_overseas, $change);
}
?>
