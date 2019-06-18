<?php
define('SVOTING', 'mysql');
define('DBHOST', 'localhost');
define('DBUSER', 'jpos');
define('DBPASS', 'bKHpxnbppftU5pY9');
define('DBNAME', 'test4');
define('NRVOT', 5);
define('USRVOTE', 1);
if(USRVOTE !== 1) {
  if(!isset($_SESSION)) session_start();
  if(isset($_SESSION['username'])) define('VOTER', $_SESSION['username']);
}
if(!headers_sent()) header('Content-type: text/html; charset=utf-8');
include('class.voting.php');
$obVot = new Voting();
if(isset($_POST['elm']) && isset($_POST['vote'])) {
  $_POST['elm'] = array_map('strip_tags', $_POST['elm']);
  $_POST['elm'] = array_map('trim', $_POST['elm']);
  if(!empty($_POST['vote'])) $_POST['vote'] = intval($_POST['vote']);
  echo $obVot->getVoting($_POST['elm'], $_POST['vote']);
}