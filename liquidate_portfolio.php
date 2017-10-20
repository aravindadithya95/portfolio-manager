<?php
session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Liquidate Portfolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
      .navbar{
        margin-bottom:0;
        border-radius:0;
      }
    </style>
  </head>
  <body>
    <center>
    <div class="container">
      <h2>Are you sure you wanna delete your portfolio? You cannot undo this action</h1>
      <form action="scripts/liquidate.php">
        <button type="submit" name="liquidate" class="btn btn-default">Liquidate Portflio</button>
      </form>
      </div>
    </center>
  </body>
</html>