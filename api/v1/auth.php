<?php
include('../../php/init.php');
include('../../Models/User.php');

$user = new User($conn);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = h($_POST["email"]);
  $pass = h($_POST["password"]);
  
  $response = $user->userLogin($email, $pass);
} else if($_SERVER["REQUEST_METHOD"] == "GET") {
  if(isset($_GET["email"])) {
    $email = h($_GET["email"]);
    $response = $user->checkEmail($email);
  } else {
    $response = array(
      'status' => 0,
      'status_message' => 'no email supplied'
    );
  }
} else {
  $response = array(
    'status'=>0,
    'status_message'=>'Invalid request method'
  ); 
}

header('Content-Type: application/json');
echo json_encode($response);

?>