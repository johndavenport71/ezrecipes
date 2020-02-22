<?php

if($_SERVER["REQUEST_METHOD"] == 'GET') {
  include('../php/init.php');

  $id = h($_GET["id"]) ?? "";

  if(isset($id)) {
    $recipe = getFullRecipe($conn, $id);
  }

  if(isset($recipe)) {
    $response = array("status"=>1, "recipe"=> $recipe->jsonSerialize());
    
  } else {
    $response = array('status'=>0, 'status_message'=>'Failed to retrieve record');
  }
  header('Content-Type: application/json');
  echo json_encode($response);
}

?>