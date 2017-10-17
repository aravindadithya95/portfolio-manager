<?php

$currency = "USDINR=X";
$url = 'https://finance.yahoo.com/quote/' . $currency;

$data = file_get_contents($url);

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($data);
libxml_clear_errors();
$dom->saveHTML();

$xpath = new DOMXPath($dom);
$rate = $xpath->query('//*[@id="quote-header-info"]/div[3]/div[1]/div/span[1]/text()');

$exc = $rate->item(0)->nodeValue;
//echo $exc;

?>
