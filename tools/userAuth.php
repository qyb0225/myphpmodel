<?php
// error_reporting(0);
session_start();
$username = $_SESSION['username'];
if(!$username) {
  header('Location: ./home.php');
}


?>
