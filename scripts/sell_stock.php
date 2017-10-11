<?php
session_start();

if (!isset($_SESSION['sell'])) {
  header("location: ../home.php");
  exit();
}
require 'database.php';

// Get session variables
$username = $_SESSION['username'];
$symbol = $_SESSION['stock'];
$shares = $_SESSION['shares'];
$price = $_SESSION['price'];
$category = ($symbol == "IS1" || $symbol == "IS2") ? "overseas" : "dow30";

// Get user's cash deposit, dow30 and overseas stocks
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$cash_deposit = $row['cash'];
$dow30_value = $row['dow30_value'];
$overseas_value = $row['overseas_value'];

$sell_value = $shares * $price;

$query = "SELECT shares FROM user_stocks WHERE username = '$username' AND symbol = '$symbol'";
$result = mysqli_query($conn, $query);
$qty = mysqli_fetch_assoc($result)['shares'];

if ($category == "dow30") {
  $dow30_value -= $sell_value;
  // Check 70-30
  if ($dow30_value >= 0.7 * ($dow30_value + $overseas_value)) {
    if ($qty == $shares) {
      $query = "DETELE FROM user_stocks WHERE username = '$username' AND symbol = '$symbol'";
      $result = mysqli_query($conn, $query);
    } else {
      $query = "UPDATE user_stocks SET shares = shares - '$shares', cost_basis = cost_basis - '$sell_value' WHERE username = '$username' AND symbol = '$symbol'";
      $result = mysqli_query($conn, $query);
    }
    $query = "INSERT INTO transactions VALUES(\"Sell\", '$username', '$symbol', now(), '$shares', '$price', '$sell_value', 0)";
    $result = mysqli_query($conn, $query);
    $query = "UPDATE users SET dow30_value = '$dow30_value', cash = cash + '$sell_value' WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
  } else {
    echo "70-30 violation";
  }
} else {
  $overseas_value -= $sell_value;
  if ($qty == shares) {
    $query = "DETELE FROM user_stocks WHERE username = '$username' AND symbol = '$symbol'";
    $result = mysqli_query($conn, $query);
  } else {
    $query = "UPDATE user_stocks SET shares = shares - '$shares', cost_basis = cost_basis - '$sell_value' WHERE username = '$username' AND symbol = '$symbol'";
    $result = mysqli_query($conn, $query);
  }
  $query = "INSERT INTO transactions VALUES(\"Sell\", '$username', '$symbol', now(), '$shares', '$price', '$sell_value', 1250)";
  $result = mysqli_query($conn, $query);
  $query = "UPDATE users SET overseas_value = '$overseas_value', cash = cash + '$sell_value' WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
}

unset($_SESSION['sell']);
header("location: ../home.php");
?>
