<?php
session_start();

$_SESSION['type'] = $_POST['type'];
$_SESSION['amount'] = $_POST['amount'];

// Make sure the request is valid
if (!isset($_POST['add'])) {
  header('location: ../home.php');
  exit;
}

if ($_POST['amount'] == '' or $_POST['amount'] == 0) {
  $_SESSION['flash'] = array("Enter an amount", 'danger');
  header('location: ../home.php');
  exit;
}

require_once 'database.php';

$username = $_SESSION['username'];
$type = $_POST['type'];
$amount = $_POST['amount'];

if ($type == 'Deposit') {
  $query = "UPDATE users SET cash = cash + '$amount' WHERE username = '$username'";
  mysqli_query($conn, $query);

  $_SESSION['flash'] = array("Transaction successful", 'success');
} else {
  include 'validation.php';
  if (!$validated) {
    $_SESSION['flash'] = array(
      "Your portfolio must be valid to withdraw cash.",
      'danger'
    );
    header('location: ../home.php');
    exit;
  }

  // Check if user has enough cash deposit
  $query = "SELECT cash FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);

  $cash = mysqli_fetch_assoc($result)['cash'];

  if ($amount > $cash) {
    $_SESSION['flash'] = array("Insufficient cash deposit. Available amount: $" . $cash, 'danger');
    header('location: ../home.php');
    exit;
  }

  $query = "UPDATE users SET cash = cash - '$amount' WHERE username = '$username'";
  mysqli_query($conn, $query);

  $_SESSION['flash'] = array("Transaction successful", 'success');
}

$query = "INSERT INTO transactions(type, username, cash_value) VALUES(
  '$type',
  '$username',
  '$amount'
)";
mysqli_query($conn, $query);

header('location: ../home.php');
exit;
?>
