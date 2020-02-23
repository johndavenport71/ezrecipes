<?php

include('../php/init.php');

if($_SERVER["REQUEST_METHOD"] == 'GET') {
  if(isset($_GET["id"])) {
    $id = intval(h($_GET["id"]));
    $stmt = $conn->prepare("SELECT first_name, last_name, display_name, profile_pic FROM users WHERE user_id = :id");
    if($stmt->execute([":id"=>$id])) {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if($data) {
        $response = array(
          'status'=>1,
          'status_message'=>'success',
          'data'=>$data
        );
      } else {
      $response = array(
        'status'=>0,
        'status_message'=>'Failed to retreive record'
      );
    }
      
    } 
  
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

  $errors = checkUser($values);

  if(!sizeof($errors)) {
    $values["pass_hash"] = password_hash($values["password"], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users VALUES (NULL, :first, :last, NULL, 'm', :password, NULL, :email)");
    $stmt->bindParam(":first", $values["first_name"]);
    $stmt->bindParam(":last", $values["last_name"]);
    $stmt->bindParam(":password", $values["pass_hash"]);
    $stmt->bindParam(":email", $values["email"]);
    try {
      $stmt->execute();
      $id = intval($conn->lastInsertId());
    } catch(PDOException $e) {
      array_push($errors, $e->getMessage());
    }
  }

  if(sizeof($errors)) {
    $response = array(
      'status'=>0,
      'status_message'=>'failed to add new user',
      'errors'=>$errors
    );
  } else {
    $response = array(
      'status'=>1,
      'status_message'=>'Added new user',
      'new_user_id'=>$id
    );
  }
}

header('Content-Type: application/json');
echo json_encode($response);

?>