<html>
<head>
    <title>View Portfolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
      .navbar{
        margin-bottom:0;
        border-radius:0;
      }
    </style>
</head>
<body>
<center>
<?php
session_start();
require 'scripts/database.php';
require 'scripts/o_betascraper.php';

/*
if (!isset($_SESSION['username'])) {
	header("location: login.php");
}
*/

$username = $_SESSION['username'];
$query = "SELECT * FROM user_stocks, stocks WHERE username = '$username' and user_stocks.symbol = stocks.symbol group by user_stocks.symbol";
$result = mysqli_query($conn, $query);

$query_cb = "SELECT sum(cost_basis) FROM user_stocks WHERE username = '$username' GROUP BY symbol";
$result_cb = mysqli_query($conn, $query_cb);

$query_sh = "SELECT sum(shares) FROM user_stocks WHERE username = '$username' GROUP BY symbol";
$result_sh = mysqli_query($conn, $query_sh);

$query_cash = "SELECT cash FROM users where username = '$username'";
$result_cash = mysqli_query($conn, $query_cash);

#$query_exp = "SELECT sept_price FROM stocks WHERE symbol = '$symbol'";
#$result_exp = mysqli_query($conn, $query_exp);

$p_cost_basis = 0;
$p_mkt_value = 0;
$s_shares = 0;

?>
  <h3>View Portfolio</h3>

  <form action="home.php" style="display: inline-block;">
		<button type="submit">Buy | Sell | Deposit | Withdraw</button>
	</form>
  <form action="view_transaction.php" style="display: inline-block;">
		<button type="submit">View Transactions</button>
	</form>
  <form action="scripts/csv.php" style="display: inline-block;">
		<button type="submit">Export as CSV</button>
	</form>
  <form action="delete_portfolio.php" style="display: inline-block;">
		<button type="submit">Delete Portfolio</button>
	</form>
  <form action="scripts/logout.php" style="display: inline-block;">
		<button type="submit">Logout</button>
	</form>

  <div class="container">
  <table class="table table-striped">
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
      <th>Beta</th>
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
        $sh = mysqli_fetch_assoc($result_sh);
        $shares = $sh['sum(shares)'];
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
        $stockname = $symbol;
        if ($category == "dow30") {
          echo $beta;
        }
        elseif ($symbol == "TATAMOTORS.NS"){
          echo $beta_tata;
        }
        elseif ($symbol == "TCS.NS") {
          echo $beta_tcs;
        }
        elseif ($symbol == "BHARTIARTL.NS") {
          echo $beta_artl;
        }
        elseif ($symbol == "KOTAKBANK.NS") {
          echo $beta_ktk;
        }
        else {
          echo "N/A"
        }

        ?>
      </td>
      <td>
        <?php
        //Calculate rate from the FV(Real time value) = (Sept 1st Value) + (1 + r)^0.123
        $query_exp = "SELECT sept_price FROM stocks WHERE symbol = '$symbol'";
        $result_exp = mysqli_query($conn, $query_exp);
        $expr = mysqli_fetch_assoc($result_exp);
        $sept_price = $expr['sept_price'];
        $fraction=$current_price/$sept_price;
        $rate=pow(($fraction), 48/365)-1;
        $futureValue=$current_price*(pow((1+$rate),30/365));
        $return=$shares*$futureValue;
        echo round($return, 2);
        ?>
      </td>
    </tr>
		<?php
		}
		?>
    <tr>
      <td>
        <?php
        echo "Cash";
        ?>
      </td>
      <td>
      </td>
      <td>
        <?php
        $c = mysqli_fetch_assoc($result_cash);
        $cash = $c['cash'];
        echo $cash;
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
        <?php
        $p_mkt_value = $p_mkt_value + $cash;
        echo $cash;
        ?>
      </td>
    </tr>
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
  </div>
  <?php
  mysqli_close($conn);
  ?>
</center>
</body>
</html>
