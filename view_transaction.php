<html>
<head>
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

  $username = $_SESSION['username'];
  $query = "SELECT * from transactions where username = '$username'";
  $result = mysqli_query($conn, $query);

  ?>
  <h3>View Transaction</h3>

  <form action="home.php" style="display: inline-block;">
		<button type="submit">Buy | Sell | Deposit | Withdraw</button>
	</form>
  <form action="view_portfolio.php" style="display: inline-block;">
		<button type="submit">View Portfolio</button>
	</form>
  <form action="scripts/logout.php" style="display: inline-block;">
		<button type="submit">Logout</button>
	</form>

  <div class="container">
  <table class="table table-striped">
    <tr>
    <th>Type</th>
    <th>Symbol</th>
    <th>Transaction Time</th>
    <th>Shares</th>
    <th>Price</th>
    <th>Overseas Price</th>
    <th>Cash Value</th>
  </tr>

  <?php
  while($record = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
			<td>
        <?php
        $type = "";
        $type = $record['type'];
        echo $type;
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
        $t_time = $record['time_stamp'];
        echo $t_time;
        ?>
      </td>
      <td>
        <?php
        $shares = $record['shares'];
        echo $shares;
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
        $o_price = $record['overseas_price'];
        echo $o_price;
        ?>
      </td>
      <td>
        <?php
        $c_value = $record['cash_value'];
        echo $c_value;
        ?>
      </td>
    </tr>
    <?php
  }
  ?>
</table>
</div>
</center>
</body>
</html>
