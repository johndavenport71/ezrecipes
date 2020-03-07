<?php

include('../../php/init.php');
include('../../Models/User.php');

$user = new User($conn);
$request = $_SERVER["REQUEST_METHOD"];

if($request == "GET") {
  //get user info
  if(isset($_GET["id"])) {
    $id = h($_GET["id"]);
    $response = $user->getUser($id);
  } else {
    $response = array(
      'status' => 0,
      'status_message' => 'No User Found'
    );
  }
} else if ($request == "POST") {
  $password = h($_POST["password"]);
  $password_confirm = h($_POST["password_confirm"]); 
  
  if($password != $password_confirm || empty($password) || empty($password_confirm)) {
    $response = array(
      'status' => 0,
      'status_message' => 'Passwords do not match'
    );
  } else {
    $user = [];
    $user["first_name"] = h($_POST["first_name"]);
    $user["last_name"] = h($_POST["last_name"]);
    $user["email"] = h($_POST["email"]);
    $user["user_auth"] = password_hash($password, PASSWORD_DEFAULT);

    $response = $user->addUser($user);
    
  }

} else if ($request == "DELETE") {
  $id = h($_GET["id"]);

  //delete user but keep recipes
  $response = $user->deleteUser($id);
  
} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method'
  );
}


header('Content-Type: application/json');
echo json_encode($response);

?>