<?php 
include('../../php/init.php');
include('../../Models/User.php');

$user = new User($conn);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $pwd["user_id"] = h($_POST["user_id"]) ?? "";
  $pwd["og_password"] = h($_POST["og_password"]) ?? "";
  $pwd["new_password"] = h($_POST["new_password"]) ?? "";
  $pwd["new_password_confirm"] = h($_POST["new_password_confirm"]) ?? "";

  $response = $user->changePassword($pwd);

} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method'
  );
}

header('Content-Type: application/json');
echo json_encode($response);

?>