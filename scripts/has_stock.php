<?php
$has_stock = false;
// Check if user has bought that stock before
$query = "SELECT * FROM transactions WHERE username = '$username' AND symbol = '$symbol'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
  $has_stock = true;
}
?>
