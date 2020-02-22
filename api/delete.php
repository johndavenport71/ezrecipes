<?php

include('../php/init.php');

if($_SERVER["REQUEST_METHOD"] == 'GET') {
  
  $recipeID = h($_GET["recipeID"]) ?? "";
  $userID = h($_GET["userID"]) ?? "";

  if(isset($recipeID) && isset($userID)) {

    $stmt = $conn->prepare("SELECT recipe_id FROM recipes WHERE user_id = :u_id AND recipe_id = :r_id");
    $stmt->execute([":u_id"=>$userID, ":r_id"=>$recipeID]);
    $isOwner = $stmt->fetchColumn();

    if($isOwner != false) {

      //delete from recipe_ingredients
      $stmt = $conn->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = :id");
      $stmt->execute([":id"=>$recipeID]);

      //delete from recipe_category
      $stmt = $conn->prepare("DELETE FROM recipe_category WHERE recipe_id = :id");
      $stmt->execute([":id"=>$recipeID]);

      //delete from ratings
      $stmt = $conn->prepare("DELETE FROM ratings WHERE recipe_id = :id");
      $stmt->execute([":id"=>$recipeID]);

      //delete recipe
      $stmt = $conn->prepare("DELETE FROM recipes WHERE recipe_id = :id");
      $stmt->execute([":id"=>$recipeID]);

      $response = array(
        'status'=>1,
        'status_message'=>'Recipe successfully deleted'
      );

      //delete recipe uploads folder
      $dir = SITE_ROOT . "/uploads/recipes/". $recipeID ."/";

      if(file_exists($dir)) {
        if(delTree($dir)) {
          $response = array(
            'status'=>1,
            'status_message'=>'Recipe and media successfully deleted'
          );
        } else {
          $response = array(
            'status'=>0,
            'status_message'=>'Failed to delete recipe media'
          );
        }
      }
    } else {
      $response = array(
        'status'=>0,
        'status_message'=>'Cannot delete a recipe that is not your own'
      );
    }
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