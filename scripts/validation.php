<?php
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$cash = $row['cash'];
$dow30_value = $row['dow30_value'];
$overseas_value = $row['overseas_value'];

$noncash_value = $dow30_value + $overseas_value;

// Calculate portfolio percentages
$dow30_percent = 0;
$overseas_percent = 0;
if ($noncash_value != 0) {
  $dow30_percent = round($dow30_value / $noncash_value * 100, 1);
  $overseas_percent = round($overseas_value / $noncash_value * 100, 1);
}

$portfolio_value = $noncash_value + $cash;

// Calculate portfolio split
$dow30_split = 0;
$overseas_split = 0;
$cash_split = 0;
if ($portfolio_value != 0) {
  $dow30_split = round($dow30_value / $portfolio_value * 100, 1);
  $overseas_split = round($overseas_value / $portfolio_value * 100, 1);
  $cash_split = round($cash / $portfolio_value * 100, 1);
}

// Check validation
$validated = true;
if ($dow30_percent < 66 or $dow30_percent > 74 or $cash_split > 11) {
    $validated = false;
}
?>
