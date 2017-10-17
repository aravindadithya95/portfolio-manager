<html>
<head>
    <title>View Portfolio</title>
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

$query_cb = "SELECT sum(cost_basis) from user_stocks where username = '$username' group by symbol";
$result_cb = mysqli_query($conn, $query_cb);

$p_cost_basis = 0;
$p_mkt_value = 0;

?>
  <h3>View Portfolio</h3>

  <form action="home.php" style="display: inline-block;">
		<button type="submit">Buy/Sell</button>
	</form>
  <form action="view_transaction.php" style="display: inline-block;">
		<button type="submit">View Transactions</button>
	</form>
  <form action="" style="display: inline-block;">
		<button type="submit">Export as CSV</button>
	</form>
  <form action="" style="display: inline-block;">
		<button type="submit">Delete Portfolio</button>
	</form>
  <form action="scripts/logout.php" style="display: inline-block;">
		<button type="submit">Logout</button>
	</form>

  <table>
    <tr>
      <th>Name</th>
      <th>Symbol</th>
      <th>Last price</th>
      <th>Price in Foreign Currency</th>
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
      $category = "dow30";
      $current_foreign_price = (int)$current_price;
      if ($symbol == "TATAMOTORS.NS" || $symbol == "TCS.NS" || $symbol == "BHARTIARTL.NS" || $symbol == "KOTAKBANK.NS") {
        $category = "overseas";
        require 'scripts/currency.php';
        $current_price /= $exc;
      }
      echo round($current_price, 2);
			?>
			</td>
      <td>
        <?php
        if ($category == "overseas")  echo $current_foreign_price;
        ?>
      </td>
      <td>
        <?php
        $stockname = $symbol;
        //require 'scripts/scraper.php';
        echo $gain_and_percent;
        ?>
      </td>
      <td>
        <?php
        $shares = "";
        $shares = $record['shares'];
        echo $shares;
        ?>
      </td>
      <td>
      <?php
      $cb = mysqli_fetch_assoc($result_cb);
      $cost_basis = $cb['sum(cost_basis)'];
      $p_cost_basis = $p_cost_basis + $cost_basis;
      echo $cost_basis;
      ?>
    </td>
      <td>
        <?php
        $market_value = $shares * $current_price;
        $p_mkt_value = $p_mkt_value + $market_value;
        echo round($market_value, 2);
        ?>
      </td>
      <td>
        <?php
        $gain = $market_value - $cost_basis;
        echo round($gain, 2);
        ?>
      </td>
      <td>
        <?php
        $gain_cent = ($gain/$cost_basis)*100;
        echo round($gain_cent, 2);
        ?>
      </td>
      <td>
        <?php
        //Calculate rate from the FV(Real time value) = (Sept 1st Value) + (1 + r)^0.123
        ?>
      </td>
    </tr>
		<?php
		}
		?>
    <tr>
      <td>
        <?php
        echo "Portfolio value";
        ?>
      </td>
      <td>
      </td>
      <td>
      </td>
      <td>
      </td>
      <td>
      </td>
      <td>
      </td>
      <td>
        <?php
        echo $p_cost_basis;
        ?>
      </td>
      <td>
        <?php
        echo round($p_mkt_value, 2);
        ?>
      </td>

    </tr>
  </table>
  <?php
  mysqli_close($conn);
  ?>
  <button type="button">Deposit/Withdraw Cash</button>
  <button type="button">Buy/Sell Stock</button>

</body>
</html>
