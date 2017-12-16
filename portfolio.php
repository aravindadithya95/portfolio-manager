<?php
session_start();

if (!isset($_SESSION['username'])) {
	header("location: login.php");
}

require_once 'scripts/database.php';
$username = $_SESSION['username'];

$query = "SELECT * FROM users where username = '$username'";
$result = mysqli_query($conn, $query);
$name = mysqli_fetch_assoc($result)['name'];

require_once 'scripts/validation.php';

$query = "SELECT * FROM user_stocks, stocks WHERE username = '$username' AND user_stocks.symbol = stocks.symbol GROUP BY user_stocks.symbol";
$result = mysqli_query($conn, $query);

$query_cb = "SELECT sum(cost_basis) FROM user_stocks WHERE username = '$username' GROUP BY symbol";
$result_cb = mysqli_query($conn, $query_cb);

$query_sh = "SELECT sum(shares) FROM user_stocks WHERE username = '$username' GROUP BY symbol";
$result_sh = mysqli_query($conn, $query_sh);

$query_cash = "SELECT cash FROM users WHERE username = '$username'";
$result_cash = mysqli_query($conn, $query_cash);

$p_cost_basis = 0;
$p_mkt_value = 0;
$s_shares = 0;
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Portfolio | <?php echo $name; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-light">
      <div class="container">
        <a class="navbar-brand" href="home.php">Portfolio Manager</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav ml-auto">
						<a class="nav-item nav-link" href="home.php">Manage</a>
            <a class="nav-item nav-link active" href="portfolio.php">Portfolio</a>
            <a class="nav-item nav-link" href="transactions.php">Transactions</a>
            <a class="nav-item nav-link font-weight-bold" href="scripts/logout.php">Logout</a>
          </div>
        </div>
      </div>
    </nav>

    <div class="container text-center">
      <h3>My Portfolio</h3>

      <div class="buttons">
				<a href="scripts/validate.php">
					<button type="button" class="btn btn-outline-primary">Validate</button>
				</a>
				<a href="liquidate.php">
					<button type="button" class="btn btn-outline-danger">Liquidate</button>
				</a>
        <a href="scripts/csv.php">
					<button type="button" class="btn btn-outline-secondary">Export as CSV</button>
				</a>
      </div>

      <!-- Flash Messages -->
      <div class="flash">
        <?php
        if (isset($_SESSION['flash'])) {?>
        <div class="alert alert-<?php echo $_SESSION['flash'][1] ?>" role="alert">
          <?php echo $_SESSION['flash'][0]; ?>
        </div>
        <?php
        }
        unset($_SESSION['flash']);
        ?>
      </div>

      <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: <?php echo $dow30_percent; ?>%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $overseas_percent; ?>%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
      </div>

      <table class="table table-sm table-hover">
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
        <?php while ($record = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td>
            <?php
            $stockname = $record['stock_name'];
            echo $stockname;
            ?>
          </td>
          <td>
            <?php
            $symbol = $record['symbol'];
            echo $symbol;
            ?>
          </td>
          <td>
            <?php
            $price = $record['price'];
            echo $price;
            ?>
          </td>
          <td>
            <?php
						$category = $record['category'];
						if ($category != 'Dow 30') {
							$price_overseas = $record['price_overseas'];
	            echo $price_overseas;
						}
            ?>
          </td>
					<?php $price_change = $record['price_change']; ?>
          <td style="<?php if ($price_change < 0) {echo 'color: red;';} else {echo 'color: green;';}?>">
            <?php
						echo $price_change;
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
            echo $cost_basis;
            $p_cost_basis += $cost_basis;
            ?>
          </td>
          <td>
            <?php
            $market_value = $shares * $price;
            echo round($market_value, 2);
            $p_mkt_value += $market_value;
            ?>
          </td>
          <td>
            <?php
            $gain = $market_value - $cost_basis;
            echo round($gain, 2);
            ?>
          </td>
					<?php $gain_percent = round($gain / $cost_basis * 100, 2); ?>
          <td style="<?php if ($gain_percent < 0) {echo 'color: red;';} else {echo 'color: green;';}?>">
            <?php
            echo $gain_percent . "%";
            ?>
          </td>
          <td>
            <?php

            ?>
          </td>
					<td>
						<?php
						//Calculate rate from the FV(Real time value) = (Sept 1st Value) + (1 + r)^0.123
		        $query_exp = "SELECT sept_price FROM stocks WHERE symbol = '$symbol'";
		        $result_exp = mysqli_query($conn, $query_exp);
		        $expr = mysqli_fetch_assoc($result_exp);
		        $sept_price = $expr['sept_price'];
		        $fraction=$price/$sept_price;
		        $rate=pow(($fraction), 48/365)-1;
		        $futureValue=$price*(pow((1+$rate),30/365));
		        $return=$shares*$futureValue;
		        echo round($return, 2);
						?>
					</td>
        </tr>
        <?php } ?>
				<tr class="table-foot">
					<td><strong>Portfolio value</strong></td>
					<td></td>
					<td>Cash:
						<?php
		        $c = mysqli_fetch_assoc($result_cash);
		        $cash = $c['cash'];
		        echo "$" . $cash;
        		?>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo "$" . $p_cost_basis; ?></td>
					<td><?php echo "$" . round($p_mkt_value, 2); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
      </table>
    </div>
  </body>
</html>
