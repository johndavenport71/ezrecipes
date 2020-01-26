<?php
include_once('db-credentials.php');
include('../db-controller.obj.php');

$dsn = "mysql:host=$host;dbname=$db;";

try {
  $conn = new PDO($dsn, $user, $pass);
  // if($conn) {
  //   $control = new DBController($conn);
  // }
} catch (PDOException $e) {
  print($e->getMessage());
}

/***** 
 * 
 * For Testing 
 * Remove Before Production
 * 
 * *****/
if($conn) {
  $conn->setAttribute("PDO::ATTR_ERRMODE", PDO::ERRMODE_EXCEPTION);
}
