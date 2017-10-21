<?php
$url = "http://www.morningstar.in/stocks/"

$url_artl = $url . "0p0000az6m/bse-bharti-airtel-ltd/overview.aspx";
$url_tcs = $url . "0p0000axt9/bse-tata-consultancy-services-ltd/overview.aspx";
$url_ktk = $url . "0p0000cba9/bse-kotak-mahindra-bank-ltd/overview.aspx";
$url_tata = $url . "0p0000azvc/nse-tata-motors-ltd/overview.aspx";

$data_artl = file_get_contents($url_artl);
$dom_artl = new DOMDocument();
libxml_use_internal_errors(true);
$dom_artl->loadHTML($data_artl);
libxml_clear_errors();
$dom_artl->saveHTML();

$data_tcs = file_get_contents($url_tcs);
$dom_tcs = new DOMDocument();
libxml_use_internal_errors(true);
$dom_tcs->loadHTML($data_tcs);
libxml_clear_errors();
$dom_tcs->saveHTML();

$data_ktk = file_get_contents($url_ktk);
$dom_ktk = new DOMDocument();
libxml_use_internal_errors(true);
$dom_ktk->loadHTML($data_ktk);
libxml_clear_errors();
$dom_ktk->saveHTML();

$data_tata = file_get_contents($url_tata);
$dom_tata = new DOMDocument();
libxml_use_internal_errors(true);
$dom_tata->loadHTML($data_tata);
libxml_clear_errors();
$dom_tata->saveHTML();

$xpath_artl = new DOMXPath($dom_artl);
$xpath_tcs = new DOMXPath($dom_tcs);
$xpath_ktk = new DOMXPath($dom_ktk);
$xpath_tata = new DOMXPath($dom_tata);

$bta_artl = $xpath_artl->query("//*[@id="ctl00_ContentPlaceHolder1_marketdata"]/div/table/tbody/tr[1]/td[2]");
$bta_tcs = $xpath_tcs->query("//*[@id="ctl00_ContentPlaceHolder1_marketdata"]/div/table/tbody/tr[1]/td[2]");
$bta_ktk = $xpath_ktk->query("//*[@id="ctl00_ContentPlaceHolder1_marketdata"]/div/table/tbody/tr[1]/td[2]");
$bta_tata = $xpath_tata->query("//*[@id="ctl00_ContentPlaceHolder1_marketdata"]/div/table/tbody/tr[1]/td[2]");

$beta_artl = $bta_artl->item(0)->nodeValue;
$beta_tcs = $bta_tcs->item(0)->nodeValue;
$beta_ktk = $bta_ktk->item(0)->nodeValue;
$beta_tata = $bta_tata->item(0)->nodeValue;

?>
