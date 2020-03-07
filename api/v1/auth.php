<?php
include('../../php/init.php');
include('../../Models/User.php');

$user = new User($conn);

if($_SERVER["REQUEST_METHOD"] != "POST") {
  $response = array(
    'status'=>0,
    'status_message'=>'Invalid request method'
  );
} else {
  $email = h($_POST["email"]);
  $pass = h($_POST["password"]);

  $response = $user->userAuth($email, $pass);
  
}

header('Content-Type: application/json');
echo json_encode($response);

?>