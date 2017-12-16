<?php
session_start();

if (!isset($_SESSION['username'])) {
  header('location: login.php');
  exit;
}

$username = $_SESSION['username'];

require 'scripts/database.php';
require_once 'scripts/validation.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Liquidate | Portfolio</title>

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
            <a class="nav-item nav-link" href="home.php">Manage</a>
            <a class="nav-item nav-link" href="portfolio.php">Portfolio</a>
            <a class="nav-item nav-link" href="transactions.php">Transactions</a>
            <a class="nav-item nav-link font-weight-bold" href="scripts/logout.php">Logout</a>
          </div>
        </div>
      </div>
    </nav>

    <div class="container text-center">
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

      <h3>Liquidate Portfolio</h3>

      <p>Are you sure you want to liquidate your portfolio? All of your stocks will be sold at current price and your cash withdrawn.</p>
      <form action="scripts/<?php if ($validated) {echo "liquidate.php";} else {echo "validate.php";} ?>" method="post">
        <button type="submit" class="btn btn-danger" name="liquidate">Liquidate</button>
      </form>
    </div>
  </body>
</html>
