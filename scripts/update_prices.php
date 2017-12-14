<?php
date_default_timezone_set('UTC');

$wait_time = 20; //seconds

while (true) {
  exec('php scraper.php');
  sleep($wait_time);

  print("Prices updated at " . date("g:i A, F j, Y"). ".\n");
}
?>
