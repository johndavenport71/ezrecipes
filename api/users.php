<?php

include('../php/init.php');
include('../models/api-controller.obj.php');

$controller = new APIController($conn);

if($_SERVER["REQUEST_METHOD"] == 'GET') {
  if(isset($_GET["id"])) {
    $id = intval(h($_GET["id"]));
    $response = $controller->getSingleUser($id); 
  
  } else {
    $response = array(
      'status'=>0,
      'status_message'=>'ID not set'
    );
  }
}

if($_SERVER["REQUEST_METHOD"] == 'POST') {

  $values["first_name"] = h($_POST["first_name"]) ?? "";
  $values["last_name"] = h($_POST["last_name"]) ?? "";
  $values["email"] = h($_POST["email"]) ?? "";
  $values["password"] = h($_POST["password"]) ?? "";
  $values["password_confirm"] = h($_POST["password_confirm"]) ?? "";

  $response = $controller->addUser($values);
}

header('Content-Type: application/json');
echo json_encode($response);

?>