<html>
<head>
</head>
<body>
<?php
session_start();
require 'scripts/database.php';

$username = "a";
$query = "SELECT * FROM stocks;";
$result = mysqli_query($conn, $query);

while($record = mysqli_fetch_array($result)) {

  echo $record['stock_name'];
  echo $record['symbol'];

}

mysqli_close($conn);
?>
</body>
</html>
