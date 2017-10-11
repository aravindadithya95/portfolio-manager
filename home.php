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
	<form action="scripts/buy_sell.php" method="post">
		<select name="type">
			<option value="buy">Buy</option>
			<option value="sell">Sell</option>
		</select>
		<select name="stock">
			<option name="aapl">AAPL</option>
			<option name="googl">GOOGL</option>
		</select>
		<input type="text" name="shares" placeholder="Shares"/>
		<button type="submit" name="add">Add to Portfolio</button>
	</form>

	<br>

	<form action="scripts/deposit_withdraw.php" method="post">
		<select name="type">
			<option value="deposit">Deposit</option>
			<option value="withdraw">Withdraw</option>
		</select>
		<input type="text" name="amount" placeholder="Amount"/>
		<button type="submit" name="add">Add to Portfolio</button>
	</form>

	<br>
	
	<form action="scripts/logout.php">
		<button type="submit" name="logout">Logout</button>
	</form>
</body>
</html>
