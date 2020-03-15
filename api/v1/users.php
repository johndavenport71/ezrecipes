<?php

include('../../php/init.php');
include('../../Models/User.php');

$controller = new User($conn);
$request = $_SERVER["REQUEST_METHOD"];

if($request == "GET") {
  //get user info
  if(isset($_GET["id"])) {
    $id = h($_GET["id"]);
    $response = $controller->getUser($id);
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
    $data = [];
    $data["first_name"] = h($_POST["first_name"]);
    $data["last_name"] = h($_POST["last_name"]);
    $data["email"] = h($_POST["email"]);
    $data["user_auth"] = password_hash($password, PASSWORD_DEFAULT);

    $response = $controller->addUser($data);
  }

} else if ($request == "DELETE") {
  $id = h($_GET["id"]);

  //delete user but keep recipes
  $response = $controller->deleteUser($id);
  
} else if ($request == "PUT") {
  $data = parsePut();
  $user = [];
  $user["user_id"] = intval(h($data["user_id"]));
  $user["first_name"] = h($data["first_name"]);
  $user["last_name"] = h($data["last_name"]);
  $user["display_name"] = h($data["display_name"]);
  $user["email"] = h($data["email"]);

  if(isset($data["file"])) {
    $target_dir = SITE_ROOT . "/uploads/users/". $user["user_id"] ."/";
    if(!file_exists($target_dir)) {
      mkdir($target_dir);
    }
    $target_file = $target_dir . $data["file"]["file_name"];
    if(file_put_contents($target_file, $data["file"]["body"])) {
      $user["profile_pic"] = substr($target_file, strpos($target_file, "uploads"));
      $uploadOK = 1;
    } else {
      $uploadOK = 0; 
    }
  } else {
    $user["profile_pic"] = "";
  }

  $response = $controller->updateUser($user["user_id"], $user);

  if(isset($uploadOK) && $uploadOK === 0) {
    $response["file_upload"] = "Failed to Upload files";
  } else {
    $response["file_upload"] = "File uploaded successfully";
  }

} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method'
  );
}


header('Content-Type: application/json');
echo json_encode($response);

?>