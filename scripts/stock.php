<?php
session_start();

$_SESSION['type'] = $_POST['type'];
$_SESSION['symbol'] = $_POST['symbol'];
$_SESSION['shares'] = $_POST['shares'];

if (isset($_POST['select'])) {
  $_SESSION['select'] = true;
  header('location: ../home.php');
  exit;
}

// Make sure the request is valid
if (!isset($_POST['add'])) {
  header('location: ../home.php');
  exit;
}

if ($_POST['shares'] == '') {
  $_SESSION['flash'] = array("Enter number of shares", 'danger');
  header('location: ../home.php');
  exit;
}

require_once 'database.php';

// Get latest price of a stock
function get_price($conn, $symbol) {
  $query = "SELECT price, price_overseas FROM stocks WHERE symbol = '$symbol'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row;
}

// Get price of stock on Sept 1
function get_sept_price($conn, $symbol) {
  $query = "SELECT sept_price, sept_price_overseas FROM stocks WHERE symbol = '$symbol'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  return $row;
}

$username = $_SESSION['username'];  // USERNAME
$type = $_POST['type']; // TYPE
$symbol = $_POST['symbol']; // SYMBOL
$shares = $_POST['shares']; // SHARES

// Get stock category
$query = "SELECT category
          FROM stocks
          WHERE symbol = '$symbol'";
$result = mysqli_query($conn, $query);
$category = mysqli_fetch_assoc($result)['category'];

if ($_POST['type'] == 'Buy') {
  // Get stock price
  include 'has_stock.php';
  $price = 0; // PRICE
  $price_overseas = 0;  // PRICE_OVERSEAS
  if ($has_stock == 'true') {
    $row = get_price($conn, $symbol);
    $price = $row['price'];
    $price_overseas = $row['price_overseas'];
  } else {
    $row = get_sept_price($conn, $symbol);
    $price = $row['sept_price'];
    $price_overseas = $row['sept_price_overseas'];
  }

  // Total amount
  $total = $shares * $price;  // TOTAL

  // Get user's cash deposit
  $query = "SELECT cash FROM users where username = '$username'";
  $result = mysqli_query($conn, $query);
  $cash = mysqli_fetch_assoc($result)['cash'];

  // Check if the user has enough cash deposit
  if ($cash < $total) {
    $_SESSION['flash'] = array("Insufficient cash deposit", 'danger');
    header('location: ../home.php');
    exit;
  }

  // Update DB
  $query = "INSERT INTO user_stocks VALUES(
    NULL,
    '$username',
    '$symbol',
    '$shares',
    '$total'
  )";
  mysqli_query($conn, $query);
  $query = "INSERT INTO transactions VALUES(
    NULL,
    '$type',
    '$username',
    '$symbol',
    now(),
    '$shares',
    '$price',
    '$price_overseas',
    -'$total'
  )";
  mysqli_query($conn, $query);

  if ($category == 'Dow 30') {
    $query = "UPDATE users
              SET dow30_value = dow30_value + '$total',
                  cash = cash - '$total'
              WHERE username = '$username'";
  } else {
    $query = "UPDATE users
              SET overseas_value = overseas_value + '$total',
                  cash = cash - '$total'
              WHERE username = '$username'";
  }
  mysqli_query($conn, $query);

  $_SESSION['flash'] = array("Transaction successful", 'success');
} else {
  // Check if user selected which stock to sell
  if (!isset($_POST['radio'])) {
    $_SESSION['flash'] = array("Select stock to sell", 'danger');
    header('location: ../home.php');
    exit;
  }

  // Get selected entry
  $selected = $_POST['radio'];

  $query = "SELECT shares, cost_basis FROM user_stocks WHERE id = '$selected'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $selected_shares = $row['shares'];
  $selected_cost_basis = $row['cost_basis'];

  // Check if there are enough shares to sell
  if ($shares > $selected_shares) {
    $_SESSION['flash'] = array("You only have " . $selected_shares . " share(s) to sell", 'danger');
    header('location: ../home.php');
    exit;
  }

  $row = get_price($conn, $symbol);
  $price = $row['price']; // PRICE
  $price_overseas = $row['price_overseas'];
  $total = $price * $shares; // TOTAL

  $selected_price = $selected_cost_basis / $selected_shares;
  $selected_total = $shares * $selected_price;

  // Update DB
  if ($shares < $selected_shares) {
    $query = "UPDATE user_stocks
              SET shares = shares - '$shares',
                  cost_basis = cost_basis - '$selected_total'
              WHERE id = '$selected'";
  } else {
    $query = "DELETE FROM user_stocks WHERE id = '$selected'";
  }
  mysqli_query($conn, $query);

  $query = "INSERT INTO transactions VALUES(
    NULL,
    '$type',
    '$username',
    '$symbol',
    now(),
    '$shares',
    '$price',
    '$price_overseas',
    '$total'
  )";
  mysqli_query($conn, $query);

  if ($category == 'Dow 30') {
    $query = "UPDATE users
              SET dow30_value = dow30_value - '$selected_total',
                  cash = cash + '$total'
              WHERE username = '$username'";
  } else {
    $query = "UPDATE users
              SET overseas_value = overseas_value - '$selected_total',
                  cash = cash + '$total'
              WHERE username = '$username'";
  }
  mysqli_query($conn, $query);

  $_SESSION['flash'] = array ("Transaction successful", 'success');
}

// Remove SESSION variables
// unset($_SESSION['type']);
// unset($_SESSION['symbol']);
unset($_SESSION['shares']);
// unset($_SESSION['select']);

header('location: ../home.php');
exit;
?>
