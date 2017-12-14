<?php
session_start();

if (!isset($_SESSION['username'])) {
	header("location: login.php");
}

require_once 'scripts/database.php';
$username = $_SESSION['username'];

$query = "SELECT * FROM users where username = '$username'";
$result = mysqli_query($conn, $query);
$name = mysqli_fetch_assoc($result)['name'];
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Transactions | <?php echo $name; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-light">
      <div class="container">
        <a class="navbar-brand" href="home.php">Portfolio Manager</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav ml-auto">
            <a class="nav-item nav-link" href="portfolio.php">Portfolio</a>
            <a class="nav-item nav-link active" href="transactions.php">Transactions</a>
            <a class="nav-item nav-link font-weight-bold" href="scripts/logout.php">Logout</a>
          </div>
        </div>
      </div>
    </nav>

    <div class="container text-center">
      <h3>My Transactions</h3>

      <div class="buttons">
        <a href="scripts/validate.php">
          <button type="button" class="btn btn-outline-primary">Validate</button>
        </a>
        <a href="liquidate.php">
          <button type="button" class="btn btn-outline-danger">Liquidate</button>
        </a>
        <a href="scripts/csv.php">
          <button type="button" class="btn btn-outline-secondary">Export to CSV</button>
        </a>
      </div>

      <!-- Flash Messages -->
      <div class="flash">
        <?php
        if (isset($_SESSION['flash'])) {?>
        <div class="alert alert-<?php echo $_SESSION['flash'][1] ?>" role="alert">
          <?php echo $_SESSION['flash'][0]; ?>
        </div>
        <?php
        }
        unset($_SESSION['flash']);
        ?>
      </div>

			<?php include 'scripts/validation.php'; ?>
			<div class="progress">
        <div class="progress-bar" role="progressbar" style="width: <?php echo $dow30_percent; ?>%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $overseas_percent; ?>%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
      </div>

			<?php
			$query = "SELECT * FROM transactions WHERE username = '$username'";
			$result = mysqli_query($conn, $query);
			?>
      <table class="table table-sm table-hover">
        <tr>
          <th>Type</th>
          <th>Symbol</th>
          <th>Transaction Time</th>
          <th>Shares</th>
          <th>Price</th>
          <th>Overseas Price</th>
          <th>Cash Value</th>
        </tr>
        <?php
        while($record = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
          <td>
          <?php
          $type = $record['type'];
          echo $type;
          ?>
          </td>
          <td>
          <?php
          $symbol = $record['symbol'];
          echo $symbol;
          ?>
          </td>
          <td>
          <?php
          $time_stamp = $record['time_stamp'];
          echo $time_stamp;
          ?>
          </td>
          <td>
          <?php
          $shares = $record['shares'];
          echo $shares;
          ?>
          </td>
          <td>
          <?php
          $price = $record['price'];
          echo $price;
          ?>
          </td>
          <td>
          <?php
					$query = "SELECT category FROM stocks WHERE symbol = '$symbol'";
					$result2 = mysqli_query($conn, $query);
					$category = mysqli_fetch_assoc($result2)['category'];
					if ($category != 'Dow 30') {
						$price_overseas = $record['price_overseas'];
	          echo $price_overseas;
					}
          ?>
          </td>
          <td>
          <?php
          $cash_value = $record['cash_value'];
          echo $cash_value;
          ?>
          </td>
				</tr>
        <?php } ?>
      </table>
    </div>
  </body>
</html>
