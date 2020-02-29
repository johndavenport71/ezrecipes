<?php
include('../php/init.php');
include('../models/api-controller.obj.php');

$controller = new APIController($conn);

if($_SERVER["REQUEST_METHOD"] != "POST") {
  $response = array(
    'status'=>0,
    'status_message'=>'Invalid request'
  );
} else {
  $email = h($_POST["email"]);
  $pass = h($_POST["password"]);

  $response = $controller->userAuth($email, $pass);
}

header('Content-Type: application/json');
echo json_encode($response);

?>