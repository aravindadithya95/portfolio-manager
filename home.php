<?php
session_start();

if (!isset($_SESSION['username'])) {
	header("location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Portfolio | Home</title>
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
<span style="float:right">
	<form action="scripts/logout.php">
	
		<button type="submit" name="logout" class="btn btn-default">Logout</button>
		
	</form>
</span>
<center>

<h1>     Portfolio Transactions</h1>

<br>

<div class="container">
	<div class="form-inline">
<form action="view_portfolio.php" style="display: inline-block;">	
		<button type="submit" class="btn btn-default">View Portfolio</button>	
</form>
<form action="view_transaction.php" style="display: inline-block;">

	<button type="submit" class="btn btn-default">View Transactions</button>

</form>
<form action="scripts/validate.php" style="display: inline-block;">

	<button type="submit" class="btn btn-default">Validate</button>

</form>
<form action="liquidate_portfolio.php" style="display: inline-block;">

	<button type="submit" class="btn btn-default">Liquidate</button>
	
</form>
</div>
</div>


	<br>

	<form action="scripts/buy_sell.php" method="post">

		<h2>Buy</h2>

		<h4>Domestic</h4>
		<div class="container">
			<div class="form-inline">
		<select name="buy_stock_dow30" class="form-control">
			<option name="aapl"
			<?php if (isset($_SESSION['buy_stock_dow30']) && $_SESSION['buy_stock_dow30'] == "AAPL") echo "selected";
			?>>AAPL</option>
			<option name="msft"
			<?php if (isset($_SESSION['buy_stock_dow30']) && $_SESSION['buy_stock_dow30'] == "MSFT") echo "selected";
			?>>MSFT</option>
			<option name="dis"
			<?php if (isset($_SESSION['buy_stock_dow30']) && $_SESSION['buy_stock_dow30'] == "DIS") echo "selected";
			?>>DIS</option>
			<option name="ibm"
			<?php if (isset($_SESSION['buy_stock_dow30']) && $_SESSION['buy_stock_dow30'] == "IBM") echo "selected";
			?>>IBM</option>
			<option name="nke"
			<?php if (isset($_SESSION['buy_stock_dow30']) && $_SESSION['buy_stock_dow30'] == "NKE") echo "selected";
			?>>NKE</option>
			<option name="pfe"
			<?php if (isset($_SESSION['buy_stock_dow30']) && $_SESSION['buy_stock_dow30'] == "PFE") echo "selected";
			?>>PFE</option>
		</select>
		&nbsp;
			<button type="submit" name="view_price_domestic" class="btn btn-default">View Price</button>
		&nbsp;
		<input type="number" step="1" min="1" name="buy_shares_dow30" placeholder="Shares"
		<?php if (isset($_SESSION['buy_shares_dow30'])) {echo "value=\"" . $_SESSION['buy_shares_dow30'] . "\"";}?>
		/>
		<?php
		echo "<br><br>";
		if (isset($_SESSION['view_price_domestic']) AND $_SESSION['view_price_domestic']) {
			echo $_SESSION['buy_stock_dow30'] . ": $" . $_SESSION['price_domestic'];
		}
		?>
		</div>
		</div>
		<h4>Overseas</h4>
		<div class="container">
			<div class="form-inline">
		<select name="buy_stock_overseas" class="form-control">
			<option name="tatamotors.ns"
			<?php if (isset($_SESSION['buy_stock_overseas']) && $_SESSION['buy_stock_overseas'] == "TATAMOTORS.NS") echo "selected";
			?>>TATAMOTORS.NS</option>
			<option name="bhartiartl.ns"
			<?php if (isset($_SESSION['buy_stock_overseas']) && $_SESSION['buy_stock_overseas'] == "BHARTIARTL.NS") echo "selected";
			?>>BHARTIARTL.NS</option>
			<option name="tcl.ns"
			<?php if (isset($_SESSION['buy_stock_overseas']) && $_SESSION['buy_stock_overseas'] == "TCS.NS") echo "selected";
			?>>TCS.NS</option>
			<option name="kotakbank.ns"
			<?php if (isset($_SESSION['buy_stock_overseas']) && $_SESSION['buy_stock_overseas'] == "KOTAKBANK.NS") echo "selected";
			?>>KOTAKBANK.NS</option>
		</select>
		&nbsp;
		<button type="submit" name="view_price_overseas" class="btn btn-default">View Price</button>
		&nbsp;
		<input type="number" step="1" min="1" name="buy_shares_overseas" placeholder="Shares"
		<?php if (isset($_SESSION['buy_shares_overseas'])) {echo "value=\"" . $_SESSION['buy_shares_overseas'] . "\"";}?>
		/>
		<?php
		echo "<br><br>";
		if (isset($_SESSION['view_price_overseas']) AND $_SESSION['view_price_overseas']) {
			echo $_SESSION['buy_stock_overseas'] . ": $" . $_SESSION['price_overseas'];
		}
		?>
		</div>
		</div>

		<h2>Sell</h2>
		<div class="container">
			<div class="form-inline">
		<select name="sell_stock" class="form-control" >
			<optgroup label="Domestic">
			<option name="aapl"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "AAPL") echo "selected";
			?>>AAPL</option>
			<option name="msft"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "MSFT") echo "selected";
			?>>MSFT</option>
			<option name="dis"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "DIS") echo "selected";
			?>>DIS</option>
			<option name="ibm"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "IBM") echo "selected";
			?>>IBM</option>
			<option name="nke"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "NKE") echo "selected";
			?>>NKE</option>
			<option name="pfe"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "PFE") echo "selected";
			?>>PFE</option>
		</optgroup>
		<optgroup label="Overseas">
			<option name="tatamotors.ns"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "TATAMOTORS.NS") echo "selected";
			?>>TATAMOTORS.NS</option>
			<option name="bhartiartl.ns"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "BHARTIARTL.NS") echo "selected";
			?>>BHARTIARTL.NS</option>
			<option name="tcl.ns"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "TCS.NS") echo "selected";
			?>>TCS.NS</option>
			<option name="kotakbank.ns"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "KOTAKBANK.NS") echo "selected";
			?>>KOTAKBANK.NS</option>
		</optgroup>
	</select>
		<button type="submit" name="select" class="btn btn-default">Select</button>
		&nbsp;
		<input type="number" step="1" min="1" name="sell_shares" placeholder="Shares"
		<?php if (isset($_SESSION['sell_shares'])) {echo "value=\"" . $_SESSION['sell_shares'] . "\"";}?>
		/>

		<br>

		<?php
		if (isset($_SESSION['select'])) {
			require 'scripts/database.php';

			$username = $_SESSION['username'];
			$symbol = $_SESSION['sell_stock'];

			$query = "SELECT * FROM user_stocks WHERE username = '$username' AND symbol = '$symbol'";
			$result = mysqli_query($conn, $query);
			?>

			<br>
			 <div class="container">
  					<table class="table table-striped">
				<tr>
					<th>Select</th>
					<th>Symbol</th>
					<th>Shares</th>
					<th>Price</th>
					<th>Cost Basis</th>
					<th>Category</th>
				</tr>
				<?php
				$count = 0;
				while ($row = mysqli_fetch_assoc($result)) {
					?>
					<tr>
						<td><input type="radio" name="radio" value=<?php echo "radio" . ++ $count; ?> /></td>
						<td><?php echo $row['symbol'] ?></td>
						<td><?php echo $row['shares'] ?></td>
						<td><?php echo $row['cost_basis'] / $row['shares'] ?></td>
						<td><?php echo $row['cost_basis'] ?></td>
						<td><?php echo $row['category'] ?></td>
					</tr>
					<?php
				}
				$_SESSION['count'] = $count;
				?>
			</table>
			</div>
			<?php
		}
		?>
		</div>
		</div>
		<br>

	<h2>Deposit/Withdraw Cash</h2>
	<div class="container">
			<div class="form-inline">
		<select name="cash_type" class="form-control">
			<option value="deposit">Deposit</option>
			<option value="withdraw">Withdraw</option>
		</select>
		&nbsp;
		<input type="number" min="1" name="amount" placeholder="Amount"
		<?php if (isset($_SESSION['amount'])) {echo "value=\"" . $_SESSION['amount'] . "\"";}?>
		/>
		<br><br>
		<button type="submit" name="add" class="btn btn-default">Add to Portfolio</button>
	</form>
	<?php
	if (isset($_SESSION['display_alert'])) {
		echo "<script>alert('" . $_SESSION['display_alert'] . "');</script>";
		unset($_SESSION['display_alert']);
	}
	?>
	</div>
	</div>
</center>
</body>
</html>
