<?php
session_start();

require_once 'database.php';
$username = $_SESSION['username'];

include 'validation.php';

if ($validated) {
  $_SESSION['flash'] = array(
    "Your portfolio is valid! You may withdraw or liquidate if you'd like to.<br>
    <strong>Non-cash split: </strong>Dow-30(<strong>" . $dow30_percent . "%</strong>), Overseas(<strong>" . $overseas_percent . "%</strong>).<br>
    <strong>Portfolio split: </strong>Dow-30(<strong>" . $dow30_split . "%</strong>), Overseas(<strong>" . $overseas_split . "%</strong>), Cash(<strong>" . $cash_split ."%</strong>)<br>
    <strong>Validation requirements: </strong>Dow 30 and Overseas stocks must be approximately at a 70:30 split, Cash value must not exceed 10% of portfolio value.",
    'success'
  );
} else {
  $_SESSION['flash'] = array(
    "Your portfolio is not valid. You cannot withdraw or liquidate until your portfolio is valid.<br>
    <strong>Non-cash split<br></strong>Dow-30(<strong>" . $dow30_percent . "%</strong>), Overseas(<strong>" . $overseas_percent . "%</strong>).<br>
    <strong>Portfolio split<br></strong>Dow-30(<strong>" . $dow30_split . "%</strong>), Overseas(<strong>" . $overseas_split . "%</strong>), Cash(<strong>" . $cash_split ."%</strong>)<br>
    <strong>Validation requirements<br></strong>Dow 30 and Overseas stocks must be approximately at a 70:30 split, Cash value must not exceed 10% of portfolio value.",
    'warning'
  );
}

header('location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
