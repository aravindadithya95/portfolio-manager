<?php
session_start();

if (!isset($_SESSION['buy'])) {
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

// Get user's cash deposit
$query = "SELECT cash FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$cash_deposit = mysqli_fetch_assoc($result)['cash'];

$already_own = true;

// Check if user already owns that stock
$query = "SELECT * FROM user_stocks WHERE username = '$username' AND symbol = '$symbol'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
  $query = "SELECT sept_price FROM stocks WHERE symbol = '$symbol'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $price = $row['sept_price'];

  $already_own = false;
}

$cash_req = $shares * $price;

// Check user's cash deposit
if ($cash_deposit >= $cash_req) {
  // Check if stock is overseas
  if ($category == "overseas") {
    $query = "SELECT dow30_value, overseas_value FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $dow30_value = $row['dow30_value'];
    $overseas_value = $row['overseas_value'];

    // Check 70-30
    $value = $overseas_value + $cash_req;
    if ($value <= 0.3 * ($dow30_value + $value)) {
      if (!$already_own) {
        $query = "INSERT INTO user_stocks VALUES('$username', '$symbol', '$shares', '$cash_req', '$category')";
        mysqli_query($conn, $query);
        $query = "INSERT INTO transactions VALUES(\"Buy\", '$username', '$symbol', now(), '$shares', '$price', -'$cash_req', 1250)";
        mysqli_query($conn, $query);
        $query = "UPDATE users SET overseas_value = overseas_value + '$cash_req', cash = cash - '$cash_req' WHERE username = '$username'";
        mysqli_query($conn, $query);
      } else {
        $query = "UPDATE user_stocks SET shares = shares + '$shares', cost_basis = cost_basis + '$cash_req' WHERE username = '$username' AND symbol = '$symbol'";
        mysqli_query($conn, $query);
        $query = "INSERT INTO transactions VALUES(\"Buy\", '$username', '$symbol', now(), '$shares', '$price', -'$cash_req', 1250)";
        mysqli_query($conn, $query);
        $query = "UPDATE users SET overseas_value = overseas_value + '$cash_req', cash = cash - '$cash_req' WHERE username = '$username'";
        mysqli_query($conn, $query);
      }
    } else {
      echo "70-30 violation";
      exit();
    }
  } else {
    if (!$already_own) {
      $query = "INSERT INTO user_stocks VALUES('$username', '$symbol', '$shares', '$cash_req', '$category')";
      mysqli_query($conn, $query);
      $query = "INSERT INTO transactions VALUES(\"Buy\", '$username', '$symbol', now(), '$shares', '$price',  '-$cash_req', 0)";
      mysqli_query($conn, $query);
      $query = "UPDATE users SET dow30_value = dow30_value + '$cash_req', cash = cash - '$cash_req' WHERE username = '$username'";
      mysqli_query($conn, $query);
    } else {
      $query = "UPDATE user_stocks SET shares = shares + '$shares', cost_basis = cost_basis + '$cash_req' WHERE username = '$username' AND symbol = '$symbol'";
      mysqli_query($conn, $query);
      $query = "INSERT INTO transactions VALUES(\"Buy\", '$username', '$symbol', now(), '$shares', '$price', -'$cash_req', 0)";
      mysqli_query($conn, $query);
      $query = "UPDATE users SET dow30_value = dow30_value + '$cash_req', cash = cash - '$cash_req' WHERE username = '$username'";
      mysqli_query($conn, $query);
    }
  }
} else {
  echo "Not enough cash deposit";
}

unset($_SESSION['buy']);
header("location: ../home.php");
?>
