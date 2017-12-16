<?php
session_start();

if (!isset($_SESSION['username'])) {
	header('location: login.php');
  exit;
}

require_once 'scripts/database.php';
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Home | Portfolio</title>

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
						<a class="nav-item nav-link active" href="home.php">Manage</a>
            <a class="nav-item nav-link" href="portfolio.php">Portfolio</a>
            <a class="nav-item nav-link" href="transactions.php">Transactions</a>
            <a class="nav-item nav-link font-weight-bold" href="scripts/logout.php">Logout</a>
          </div>
        </div>
      </div>
    </nav>

    <div class="container text-center">
			<h3>Manage Portfolio</h3>

      <div class="buttons">
				<a href="scripts/validate.php">
					<button type="button" class="btn btn-outline-primary">Validate</button>
				</a>
				<a href="liquidate.php">
					<button type="button" class="btn btn-outline-danger">Liquidate</button>
				</a>
				<a href="scripts/csv.php">
          <button type="button" class="btn btn-outline-secondary">Export as CSV</button>
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

      <!-- Buy/Sell -->
      <h4>Buy or Sell</h4>
      <form class="" action="scripts/stock.php" method="post">
        <table class="table table-sm">
          <tr>
            <th>Type</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Shares</th>
						<th></th>
          </tr>
          <tr>
            <td>
              <select name="type" class="form-control">
                <option name="buy">Buy</option>
                <option name="sell" <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'Sell') echo "selected"; ?>>Sell</option>
              </select>
            </td>
            <td>
              <select name="symbol" class="form-control">
                <optgroup label="Dow 30">
                  <?php
                  $query = "SELECT symbol FROM stocks WHERE category = 'Dow 30'";
                  $result = mysqli_query($conn, $query);
                  while ($row = mysqli_fetch_assoc($result)) {
                    $symbol = $row['symbol'];
                  ?>
                  <option name="<?php echo $symbol; ?>" <?php if(isset($_SESSION['symbol']) and $_SESSION['symbol'] == $symbol) echo "selected"; ?>>
                    <?php echo $symbol; ?>
                  </option>
                  <?php } ?>
                </optgroup>
                <optgroup label="BSE 30">
                  <?php
                  $query = "SELECT symbol FROM stocks WHERE category = 'BSE 30'";
                  $result = mysqli_query($conn, $query);
                  while ($row = mysqli_fetch_assoc($result)) {
                    $symbol = $row['symbol'];
                  ?>
                  <option name="<?php echo $symbol; ?>" <?php if(isset($_SESSION['symbol']) and $_SESSION['symbol'] == $symbol) echo "selected"; ?>>
                    <?php echo $symbol; ?>
                  </option>
                  <?php } ?>
                </optgroup>
              </select>
            </td>
            <td>
              <button type="submit" name="select" class="btn btn-outline-primary">Select</button>
              <?php
              if (isset($_SESSION['select'])) {
                $symbol = $_SESSION['symbol'];
                $query = "SELECT * FROM stocks WHERE symbol = '$symbol'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                include 'scripts/has_stock.php';
                if ($has_stock) {
                  echo "<br>" . $symbol . ": <strong>$" . $row['price'] . "</strong>";
                } else {
                  echo "<br>" . $symbol . ": <strong>$" . $row['sept_price'] . "</strong>";
                }
              }
              ?>
            </td>
            <td>
              <input type="text" name="shares" placeholder="Enter shares" class="form-control" value="<?php if (isset($_SESSION['shares'])) echo $_SESSION['shares']; ?>">
            </td>
						<td>
							<button type="submit" name="add" class="btn btn-outline-success">Add to Portfolio</button>
						</td>
          </tr>
        </table>
        <?php
        if (isset($_SESSION['select'])) {
          if ($_SESSION['type'] == 'Sell') {
            $symbol = $_SESSION['symbol'];
            $query = "SELECT * FROM user_stocks WHERE username = '$username' AND symbol = '$symbol'";
            $result = mysqli_query($conn, $query);
        ?>
        <h5>Select stock to sell</h5>
        <table class="table table-sm table-hover">
          <tr>
            <th>Select</th>
            <th>Stock</th>
            <th>Shares</th>
            <th>Price</th>
            <th>Cost Basis</th>
          </tr>
          <?php
          while ($row = mysqli_fetch_assoc($result)) {
          ?>
          <tr>
            <td><input type="radio" name="radio" value=<?php echo $row['id'] ?>></td>
            <td><?php echo $row['symbol'] ?></td>
            <td><?php echo $row['shares'] ?></td>
            <td><?php echo $row['cost_basis'] / $row['shares'] ?></td>
            <td><?php echo $row['cost_basis'] ?></td>
          </tr>
          <?php
          }
          ?>
        </table>
        <?php
          }
        }
        ?>
        <!--button type="submit" name="add" class="btn btn-success">Add to Portfolio</button-->
      </form>


      <!-- Deposit/Withdraw -->
      <h4 class="heading">Deposit or Withdraw</h4>
      <form class="" action="scripts/cash.php" method="post">
        <table class="table table-sm">
          <tr>
            <th scope="col">Type</th>
            <th scope="col">Amount</th>
						<th></th>
          </tr>
          <td>
            <select class="form-control" name="type">
              <option name="deposit">Deposit</option>
              <option name="withdraw" <?php if(isset($_SESSION['type']) and $_SESSION['type'] == 'Withdraw') echo "selected"; ?>>Withdraw</option>
            </select>
          </td>
          <td>
            <input type="text" name="amount" placeholder="Enter amount" class="form-control" value="<?php if (isset($_SESSION['amount'])) echo $_SESSION['amount']; ?>">
          </td>
					<td>
						<button type="submit" name="add" class="btn btn-outline-success">Add to Portfolio</button>
					</td>
        </table>
        <!--button type="submit" name="button" class="btn btn-success">Add to Portfolio</button-->
      </form>

      <footer>
        <br><br><br>
      </footer>
    </div>
  </body>
</html>
