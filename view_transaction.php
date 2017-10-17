<html>
<head>
</head>
<body>
  <?php
  session_start();
  require 'scripts/database.php';

  $username = "a";
  $query = "SELECT * from transactions where username = '$username'";
  $result = mysqli_query($conn, $query);

  ?>
  <h3>View Transaction</h3>

  <button type="button">Back to View Portfolio</button>
  <button type="button">Logout</button>

  <table>
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
</body>
</html>
