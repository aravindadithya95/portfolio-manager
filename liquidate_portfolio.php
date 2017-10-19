<?php
session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Liquidate Portfolio</title>
  </head>
  <body>
    <center>
      <h1>Are you sure you wanna delete your portfolio? You cannot undo this action</h1>
      <form action="scripts/liquidate.php">
        <button type="submit" name="liquidate">Liquidate Portflio</button>
      </form>
    </center>
  </body>
</html>