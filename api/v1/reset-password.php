<?php
include('../../php/init.php');
include('../../Models/User.php');

$user = new User($conn);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $data["password"] = h($_POST["password"]) ?? "";
  $data["password_confirm"] = h($_POST["password_confirm"]) ?? "";
  $data["selector"] = h($_POST["selector"]) ?? "";
  $data["token"] = h($_POST["token"]) ?? "";

  $response = $user->resetPassword($data);

} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method'
  );
}

header('Content-Type: application/json');
echo json_encode($response);

?>