<?php
session_start();
if (!isset($_SESSION['username'])) {
        header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Delete Portfolio</title>
  </head>
  <body>
    <center>
      <h1>Are you sure you wanna delete your portfolio? You cannot undo this action</h1>
      <form action="scripts/delete.php">
        <button type="submit" name="button">Delete Portflio</button>
      </form>
    </center>
  </body>
</html>
