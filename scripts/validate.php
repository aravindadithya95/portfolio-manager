<?php
session_start();
$username = $_SESSION['username'];

require 'database.php';

$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$dow30_value = $row['dow30_value'];
$overseas_value = $row['overseas_value'];
$cash = $row['cash'];

$dow30_percent = round($dow30_value / ($dow30_value + $overseas_value) * 100, 1);
if (is_nan($dow30_percent)) {
    $dow30_percent = 0;
}
$overseas_percent = round($overseas_value / ($dow30_value + $overseas_value) * 100, 1);
if (is_nan($overseas_percent))    
    $overseas_percent = 0;
$cash_percent = round($cash / ($dow30_value + $overseas_value) * 100, 1);
if (is_nan($cash_percent))
    $cash_percent = 0;


$dow30_split = round($dow30_value / ($dow30_value + $overseas_value + $cash) * 100, 1);
if (is_nan($dow30_split))
    $dow30_split = 0;
$overseas_split = round($overseas_value / ($dow30_value + $overseas_value + $cash) * 100, 1);
if (is_nan($overseas_split))
    $overseas_split = 0;
$cash_split = round($cash / ($dow30_value + $overseas_value + $cash) * 100, 1);
if (is_nan($cash_split))
    $cash_split = 0;

$condition = true;
if ($dow30_percent < 67 or $dow30_percent > 73 or $cash_percent > 10) {
    $condition = false; 
}

$message = "Non-cash split: Domestic: " . $dow30_percent . "%, Overseas: " . $overseas_percent . "%";
$message = $message . ". Portfolio split: Domestic: " . $dow30_split . "%, Overseas: " . $overseas_split . "%, Cash: " . $cash_split . "%.";
$_SESSION['display_alert'] = $message;
header("location: ../home.php");
exit();
?>