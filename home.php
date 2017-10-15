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
</head>
<body>
<center>
	<form action="scripts/logout.php">
		<button type="submit" name="logout">Logout</button>
	</form>

	<br>

	<form action="scripts/buy_sell.php" method="post">
		<h1>Buy</h1>
		<select name="buy_stock">
			<option name="aapl"
			<?php if (isset($_SESSION['buy_stock']) && $_SESSION['buy_stock'] == "AAPL") echo "selected";
			?>>AAPL</option>
			<option name="googl"
			<?php if (isset($_SESSION['buy_stock']) && $_SESSION['buy_stock'] == "GOOGL") echo "selected";
			?>>GOOGL</option>
			<option name="is1"
			<?php if (isset($_SESSION['buy_stock']) && $_SESSION['buy_stock'] == "IS1") echo "selected";
			?>>IS1</option>
		</select>
		<input type="number" step="1" min="1" name="buy_shares" placeholder="Shares"
		<?php if (isset($_SESSION['buy_shares'])) {echo "value=\"" . $_SESSION['buy_shares'] . "\"";}?>
		/>
		<input type="number" name="buy_price" placeholder="Price"
		<?php if (isset($_SESSION['buy_price'])) {echo "value=\"" . $_SESSION['buy_price'] . "\"";}?>
		/>

		<h1>Sell</h1>
		<select name="sell_stock">
			<option name="aapl"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "AAPL") echo "selected";
			?>>AAPL</option>
			<option name="googl"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "GOOGL") echo "selected";
			?>>GOOGL</option>
			<option name="is1"
			<?php if (isset($_SESSION['sell_stock']) && $_SESSION['sell_stock'] == "IS1") echo "selected";
			?>>IS1</option>
		</select>
		<button type="submit" name="select">Select</button>
		<input type="number" step="1" min="1" name="sell_shares" placeholder="Shares"
		<?php if (isset($_SESSION['sell_shares'])) {echo "value=\"" . $_SESSION['sell_shares'] . "\"";}?>
		/>
		<input type="number" name="sell_price" placeholder="Price"
		<?php if (isset($_SESSION['sell_price'])) {echo "value=\"" . $_SESSION['sell_price'] . "\"";}?>
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
			<table>
				<tr>
					<th>Select</th>
					<th>Symbol</th>
					<th>Shares</th>
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
						<td><?php echo $row['cost_basis'] ?></td>
						<td><?php echo $row['category'] ?></td>
					</tr>
					<?php
				}
				$_SESSION['count'] = $count;
				?>
			</table>

			<?php
		}
		?>

		<br>

		<button type="submit" name="add">Add to Portfolio</button>
	</form>

	<br><br>
	<h1>Deposit/Withdraw Cash</h1>

	<form action="scripts/deposit_withdraw.php" method="post">
		<select name="type">
			<option value="deposit">Deposit</option>
			<option value="withdraw">Withdraw</option>
		</select>
		<input type="text" name="amount" placeholder="Amount"/>
		<br><br>
		<button type="submit" name="add">Add to Portfolio</button>
	</form>
</center>
</body>
</html>
