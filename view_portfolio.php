<?php
session_start();
require 'scripts/database.php';

$username = $_SESSION['username'];

$query = "SELECT * FROM users WHERE username = '$username'";
echo $query;

?>
<html>
<head>
  <title>View Portfolio</title>

</head>
<body>
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
  </table>
</body>
</html>
