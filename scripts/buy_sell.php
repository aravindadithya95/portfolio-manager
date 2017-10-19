<?php
session_start();
$username = $_SESSION['username'];

$buy_stock_dow30 = $_SESSION['buy_stock_dow30'] = $_POST['buy_stock_dow30'];
$buy_shares_dow30 = $_SESSION['buy_shares_dow30'] = $_POST['buy_shares_dow30'];
$buy_price_dow30 = $_SESSION['buy_price_dow30'] = 0;

$buy_stock_overseas = $_SESSION['buy_stock_overseas'] = $_POST['buy_stock_overseas'];
$buy_shares_overseas = $_SESSION['buy_shares_overseas'] = $_POST['buy_shares_overseas'];
$buy_price_overseas = $_SESSION['buy_price_overseas'] = 0;

$sell_stock = $_SESSION['sell_stock'] = $_POST['sell_stock'];
$sell_shares = $_SESSION['sell_shares'] = $_POST['sell_shares'];
$sell_price = $_SESSION['sell_price'] = 0;

$sell_category = "dow30";
require 'database.php';

$selected_shares = 0;
$selected_cost_basis = 0;
if (isset($_POST['add'])) {
  $buy_dow30 = false;
  $buy_overseas = false;
  $sell = false;
  // Check if user wants to sell stock
  if ($_POST['sell_shares'] != "" AND isset($_POST['radio'])) {
    $radio = $_POST['radio'];
    $sell = true;
    if ($sell_stock == "BHARTIARTL.NS" or $sell_stock == "TCS.NS" or $sell_stock == "KOTAKBANK.NS" or $sell_stock == "TATAMOTORS.NS")
      $sell_category = "overseas";

    $count = 1;
    $query = "SELECT shares, cost_basis FROM user_stocks WHERE username = '$username' AND symbol = '$sell_stock'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    while ($count < $radio) {
      $row = mysqli_fetch_assoc($result);
    }
    $selected_shares = $row['shares'];
    $selected_cost_basis = $row['cost_basis'];
    $selected_stock_price = $selected_cost_basis / $selected_shares;
    if ($sell_shares > $selected_shares) {
      echo $selected_shares . " shares available";
    }
  }

  // Check if user wants to buy dow30 stock
  if ($_POST['buy_shares_dow30'] != "") {
    $buy_dow30 = true;
  }

  // Check if user wants to buy overseas stock
  if ($_POST['buy_shares_overseas'] != "") {
    $buy_overseas = true;
  }

  $cash_transaction = true;
  $cash_type = "deposit";
  $amount = 0;
  // Check if user wants to deposit/withdraw cash
  if ($_POST['cash_type'] != "") {
      if ($_POST['cash_type'] == "withdraw") {
        $cash_type = "withdraw";
      }
      $amount = $_POST['amount'];
  }

  // Current portfolio value
  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $cash = $row['cash'];
  $dow30_value = $row['dow30_value'];
  $overseas_value = $row['overseas_value'];

  // Check 70-30 and 10%
  $temp_cash = $cash;
  $temp_dow30_value = $dow30_value;
  $temp_overseas_value = $overseas_value;
  echo $temp_cash . "<br>";
  echo $temp_dow30_value . "<br>";
  echo $temp_overseas_value . "<br>";
  if ($buy_dow30) {
    $cost_basis = $buy_price_dow30 * $buy_shares_dow30;

    $query = "SELECT * FROM transactions WHERE username = '$username' AND symbol = '$buy_stock_dow30'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
      echo "Using September price";
      $query = "SELECT sept_price FROM stocks WHERE symbol = '$buy_stock_dow30'";
      $result = mysqli_query($conn, $query);
      $buy_price_dow30 = mysqli_fetch_assoc($result)['sept_price'];
    } else {
      $stockname = $buy_stock_dow30;
      require 'scraper.php';
      $buy_price_dow30 = $current_price;
    }
    $cost_basis =  $buy_price_dow30 * $buy_shares_dow30;

    $temp_dow30_value += $cost_basis;
    $temp_cash -= $cost_basis;
  }
  $buy_overseas_foreign_price = 0;
  if ($buy_overseas) {
    $query = "SELECT * FROM transactions WHERE username = '$username' AND symbol = '$buy_stock_overseas'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
      echo "Using September price";
      $query = "SELECT sept_price, sept_price_overseas FROM stocks WHERE symbol = '$buy_stock_overseas'";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      $buy_price_overseas = $row['sept_price'];
      $buy_overseas_foreign_price = $row['sept_price_overseas'];
    } else {
      $stockname = $buy_stock_overseas;
      require 'scraper.php';
      require 'currency.php';
      $buy_price_overseas = $current_price / $exc;
      $buy_overseas_foreign_price = $current_price;
    }
    $cost_basis = $buy_price_overseas * $buy_shares_overseas;

    $temp_overseas_value += $cost_basis;
    $temp_cash -= $cost_basis;
  }
  $sell_foreign_value = 0;
  if ($sell) {
    $stockname = $sell_stock;
    require 'scraper.php';
    $sell_price = $current_price;

    if ($sell_category == "overseas") {
        $sell_foreign_value = $sell_price;
        require 'currency.php';
        $sell_price /= $exc;
    }

    $cost_basis = $sell_price * $sell_shares;
    if ($sell_category == "dow30") {
      $temp_dow30_value -= $cost_basis;
    } else {
      $temp_overseas_value -= $cost_basis;
    }
    $temp_cash += $cost_basis;
  }

  if ($cash_transaction) {
      if ($cash_type == "deposit") {
          $temp_cash += $amount;
      } else {
          $temp_cash -= $amount;
      }
  }

  echo "<br>" . $temp_cash . "<br>";
  echo $temp_dow30_value . "<br>";
  echo $temp_overseas_value . "<br>";
  if ($temp_cash < 0) {
    echo "Not enough cash deposit";
    exit();
  }
  $total_value = $temp_dow30_value + $temp_overseas_value;
  if ($temp_cash > 0.1 * $total_value) {
    echo "Cash exceeding 10% of portfolio value";
    exit();
  }
  if ($temp_overseas_value < 0.28 * $total_value OR $temp_overseas_value > 0.32 * $total_value) {
    echo "70-30 imbalance" . "<br>";
    if ($temp_overseas_value > 0.32 * $total_value) {
        echo "Buy more domestic stock in value or less overseas stock in value";
    } else {
        echo "Buy more overseas stock in value or less domestic stock in value";
    }
    exit();
  }

  if ($buy_dow30) {
    $query = "INSERT INTO user_stocks VALUES('$username', '$buy_stock_dow30', '$buy_shares_dow30', '$buy_shares_dow30' * '$buy_price_dow30', \"dow30\")";
    mysqli_query($conn, $query);
    $query = "INSERT INTO transactions VALUES(\"Buy\", '$username', '$buy_stock_dow30', now(), '$buy_shares_dow30', '$buy_price_dow30', 0, -'$buy_shares_dow30' * '$buy_price_dow30')";
    mysqli_query($conn, $query);
  }
  if ($buy_overseas) {
    $query = "INSERT INTO user_stocks VALUES('$username', '$buy_stock_overseas', '$buy_shares_overseas', '$buy_shares_overseas' * '$buy_price_overseas', \"overseas\")";
    mysqli_query($conn, $query);
    $query = "INSERT INTO transactions VALUES(\"Buy\", '$username', '$buy_stock_overseas', now(), '$buy_shares_overseas', '$buy_price_overseas', $buy_overseas_foreign_price, -'$buy_shares_overseas' * '$buy_price_overseas')";
    mysqli_query($conn, $query);
  }
  if ($sell) {
    if ($sell_shares < $selected_shares) {
      $query = "UPDATE user_stocks SET WHERE username = '$username' AND symbol = '$sell_stock' AND cost_basis = '$selected_cost_basis'";
    } else {
      $query = "DELETE user_stocks WHERE username = '$username' AND symbol = '$sell_stock'";
    }
    mysqli_query($conn, $query);
    $query = "INSERT INTO transactions VALUES(\"Sell\", '$username', '$sell_stock', now(), '$sell_shares', '$sell_price', $sell_foreign_value, -'$sell_shares' * '$sell_price')";
    mysqli_query($conn, $query);
  }
  if ($cash_transaction) {
      if ($cash_type == "deposit") {
          $query = "INSERT INTO transactions VALUES(\"Deposit Cash\", '$username', \"\", now(), 0, 0, 0, '$amount')";
          $result = mysqli_query($conn, $query);
      } else {
          $query = "INSERT INTO transactions VALUES(\"Withdraw Cash\", '$username', \"\", now(), 0, 0, 0, -'$amount')";
          $result = mysqli_query($conn, $query);
      }
  }
  $query = "UPDATE users SET dow30_value = '$temp_dow30_value', overseas_value = '$temp_overseas_value', cash = '$temp_cash' WHERE username = '$username'";
  mysqli_query($conn, $query);

  unset($_SESSION['buy_stock']);
  unset($_SESSION['buy_shares']);
  unset($_SESSION['buy_price']);
  unset($_SESSION['sell_stock']);
  unset($_SESSION['sell_shares']);
  unset($_SESSION['sell_price']);
  unset($_SESSION['select']);
  //header("location: ../home.php");
  exit();
}

if (isset($_POST['select'])) {
  $_SESSION['select'] = true;

  header("location: ../home.php");
  exit();
}
if (isset($_POST['view_price_domestic'])) {
    $_SESSION['view_price_domestic'] = true;
    $current_price = 0;
    $query = "SELECT * FROM transactions WHERE username = '$username' AND symbol = '$buy_stock_dow30'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $stockname = $buy_stock_dow30;
        require 'scraper.php';
        $_SESSION['price_domestic'] = $current_price;
    } else {
        $query = "SELECT sept_price FROM stocks WHERE symbol = '$buy_stock_dow30'";
        $result = mysqli_query($conn, $query);
        $_SESSION['price_domestic'] = mysqli_fetch_assoc($result)['sept_price'];
    }

    header("location: ../home.php");
    exit();
}
if (isset($_POST['view_price_overseas'])) {
    $_SESSION['view_price_overseas'] = true;
    $current_price = 0;
    $query = "SELECT * FROM transactions WHERE username = '$username' AND symbol = '$buy_stock_overseas'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $stockname = $buy_stock_overseas;
        require 'scraper.php';
        require 'currency.php';
        $_SESSION['price_overseas'] = round($current_price / $exc, 2);
    } else {
        $query = "SELECT sept_price FROM stocks WHERE symbol = '$buy_stock_overseas'";
        $result = mysqli_query($conn, $query);
        $_SESSION['price_overseas'] = mysqli_fetch_assoc($result)['sept_price'];
    }

    header("location: ../home.php");
    exit();
}

/*
if (!isset($_POST['add'])) {
  header("location: ../home.php");
  exit();
}

$_SESSION['stock'] = $_POST['stock'];
$_SESSION['shares'] = $_POST['shares'];
$_SESSION['price'] = 100;

if ($_POST['type'] == "buy") {
  $_SESSION['buy'] = true;
  header("location: buy_stock.php");
} else {
  $_SESSION['sell'] = true;
  header("location: sell_stock.php");
}
*/
?>
