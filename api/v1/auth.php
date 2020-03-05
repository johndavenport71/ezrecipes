<?php
include('../../php/init.php');
include(MODELS . '/ApiController.php');

$controller = new ApiController($conn);

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