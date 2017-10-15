<?php
session_start();
require 'scripts/database.php';

/*
if (!isset($_SESSION['username'])) {
	header("location: login.php");
}
*/

$username = "a";

$query = "SELECT * FROM stocks where symbol = 'AAPL'";
$result = mysqli_query($conn, $query);

?>
<html>
<head>
  <title>View Portfolio</title>

</head>
<body>
  <h3>View Portfolio</h3>
  <table>
    <tr>
      <th>Name</th>
      <th>Symbol</th>
      <th>Last price</th>
      <th>Change</th>
      <th>Shares</th>
      <th>Cost Basis</th>
      <th>Market Value</th>
      <th>Gain</th>
      <th>Gain %</th>
      <th>Expected Return</th>
    </tr>
		<?php
		while ($row = mysqli_fetch_assoc($result)) {
		?>
    <tr>
			<td>
			<?php
			echo row['stock_name'];
			?>
		</td>
		<td>
			<?php
			$stockname = row['symbol'];
			echo row['symbol'];
			?>
		</td>
		<td>
			<?php
			$url = 'https://finance.yahoo.com/quote/' . $stockname;

			$data = file_get_contents($url);
			$dom = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML($data);
			libxml_clear_errors();
			$dom->saveHTML();

			$xpath = new DOMXPath($dom);
			$l_price = $xpath->query('//*[@id="quote-header-info"]/div[3]/div[1]/div/span[1]/text()');
			$current = $l_price->item(0)->nodeValue;

			echo $current;
			?>
			</td>
    </tr>
		<?php
		}
		?>
  </table>
</body>
</html>
