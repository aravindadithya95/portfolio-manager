<?php
session_start();

if (!isset($_POST['add'])) {
  header("location: ../home.php");
  exit();
}

$username = $_SESSION['username'];
$amount = $_POST['amount'];

require 'database.php';

$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$cash = $row['cash'];
$dow30_value = $row['dow30_value'];
$overseas_value = $row['overseas_value'];
if ($_POST['type'] == "deposit") {
    if ($amount + $cash <= 0.1 * ($dow30_value + $overseas_value)) {
        $query = "UPDATE users SET cash = cash + '$amount' WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        $query = "INSERT INTO transactions VALUES(\"Deposit Cash\", '$username', \"\", now(), 0, 0, 0, '$amount')";
        $result = mysqli_query($conn, $query);
    } else {
        echo "Too much cash";
        exit();
    }
} else {
  if ($cash >= $amount) {
    $query = "UPDATE users SET cash = cash - '$amount' WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $query = "INSERT INTO transactions VALUES(\"Withdraw Cash\", '$username', \"\", now(), 0, 0, 0, -'$amount')";
    $result = mysqli_query($conn, $query);
} else {
    echo "Not enough funds";
    exit();
}
}

header("location: ../home.php");
?>
