<?php
include('../../php/init.php');
include('../../Models/User.php');

$user = new User($conn);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = h($_POST["email"]);

  $response = $user->resetPassword($email);

} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method'
  );
}

header('Content-Type: application/json');
echo json_encode($response);

?>