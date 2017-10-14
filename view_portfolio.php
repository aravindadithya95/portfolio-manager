<?php
session_start();
require 'scripts/database.php';

if (!isset($_SESSION['username'])) {
	header("location: login.php");
}

$username = $_SESSION['username'];

$query = "SELECT * FROM USER_STOCKS";
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
    <tr>
    </tr>
  </table>
</body>
</html>
