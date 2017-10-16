<html>
<head>
</head>
<body>
<?php
session_start();
require 'scripts/database.php';

/*
if (!isset($_SESSION['username'])) {
	header("location: login.php");
}
*/

$username = "a";
$query = "SELECT * FROM user_stocks, stocks WHERE username = '$username' and user_stocks.symbol = stocks.symbol";
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
		while ($record = mysqli_fetch_assoc($result)) {
		?>
    <tr>
			<td>
			<?php
			$stockname =  "";
      $stockname = $record['stock_name'];
      echo $stockname;
			?>
		</td>
		<td>
			<?php
      $symbol = "";
      $symbol = $record['symbol'];
      echo $symbol;
			?>
		</td>
		<td>
			<?php
      $stockname = $symbol;
      require 'scripts/scraper.php';
      echo $current_price;
			?>
			</td>
      <td>
        <?php
        $stockname = $symbol;
        require 'scripts/scraper.php';
        echo $gain_and_percent;
        ?>
      </td>
    </tr>
		<?php
		}
		?>
  </table>
  <?php
  mysqli_close($conn);
  ?>
</body>
</html>
