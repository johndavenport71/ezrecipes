<?php
include_once('db-credentials.php');

$dsn = "mysql:host=$host;dbname=$db;";

try {
  $conn = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
  print($e->getMessage());
}

//for testing remove before production
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
