<?php
//session_start();
#$stockname = "COALINDIA.NS";
#$stockname = "AAPL";
#$stockname = "GOOG";
#$stockname = "TCS.NS";
//$stockname = $_SESSION['stockname'];

// Enter symbol into a username variable before requiring scraper.php
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
$stock_beta = $xpath->query('//*[@id="quote-summary"]/div[2]/table/tbody/tr[2]/td[2]/span/text()');
#$change = $xpath->query('//*[@id="quote-header-info"]/div[3]/div[1]/div/span[2]')
#node = $xpath->query('//*[@id="quote-header-info"]/[class="D(ib) Mend(20px)"]/span[0]/text()')
#echo "Stockname:  " . $stockname;
#echo "<br>";
$current_price = $price->item(0)->nodeValue;
$gain_and_percent = $sign->item(0)->nodeValue;// . $change->item(0)->nodeValue;
if (isset($change->item(0)->nodeValue)) {
    $gain_and_percent = (string)$gain_and_percent . $change->item(0)->nodeValue;
}
$current_price = str_replace(",", "", $current_price);
$beta = $stock_beta->item(0)->nodeValue;

#echo $current_price;
#echo "<br>";
#echo "Gain (Gain %):  ". $gain_and_percent;

// Access values directly using variable names $current_price and $gain_and_percent

//$_SESSION['price'] = $current;
#echo $nodes;

#$items = $dom -> getElementsByTagName('h1');

#for($i = 0; $i < $items->length; $i++)
#  echo $items->item($i)->nodeValue . "<br/>";
#$xpath = new DOMXPath($dom);
#$nodes = $xpath->query('//*[@id="quote-header-info"]/div[3]/div[1]/div/span[1]/text()')
#echo $doc->saveXML();

/*
$html_page = curl_init($url);
curl_setopt($html_page, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($html_page);
curl_close($html_page);
echo $html;

#$dom = new DOMDocument;
#$dom = loadHTML($html)
#foreach ($dom->getElementsByTagName('a') as $node) {
#    echo $dom->saveHtml($node), PHP_EOL;
#}


/*
$html =
$xml = simplexml_load_file($url);
print_r($sxml);

var_dump($sxml);
echo '<pre>';

echo '</pre>';
*/

?>
