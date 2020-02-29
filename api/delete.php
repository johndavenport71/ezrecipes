<?php

include('../php/init.php');
include('../models/api-controller.obj.php');

$controller = new APIController($conn);

if($_SERVER["REQUEST_METHOD"] == 'GET') {
  
  $recipeID = h($_GET["recipeID"]) ?? "";
  $userID = h($_GET["userID"]) ?? "";

  if(intval($recipeID) && intval($userID)) {
    $response = $controller->deleteRecipe($recipeID, $userID);
  } else {
    $response = array(
      'status'=>0,
      'status_message'=>'No ID provided'
    );
  }
  
  header('Content-Type: application/json');
  echo json_encode($response);
}

?>