<?php
include('../php/init.php');

if($_SERVER["REQUEST_METHOD"] == 'GET') {
  $response = array('status'=>1,'status_message'=>'TO DO');
  header('Content-Type: application/json');
  echo json_encode($response);
}

?>