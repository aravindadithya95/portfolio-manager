<?php
session_start();
if (!isset($_SESSION['username'])) {
        header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Portfolio | Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8"> 
  <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
      .navbar{
        margin-bottom:0;
        border-radius:0;
      }
    </style>
    <title>Delete Portfolio</title>
  </head>
  <body>
    <center>
    <div class="container">
      <h1>Are you sure you wanna delete your portfolio? You cannot undo this action</h1>
      <form action="scripts/delete.php">
        <button type="submit" name="button" class="btn btn-default">Delete Portflio</button>
      </form>
      </div>
    </center>
  </body>
</html>
