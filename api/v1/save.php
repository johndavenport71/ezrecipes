<?php
include('../../php/init.php');
include('../../Models/User.php');

$user = new User($conn);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $userID = intval(h($_POST["user_id"]));
  $recipeID = intval(h($_POST["recipe_id"]));
  
  $response = $user->saveRecipe($userID, $recipeID);
} else if($_SERVER["REQUEST_METHOD"] == "DELETE") {
  $userID = intval(h($_GET["user_id"]));
  $recipeID = intval(h($_GET["recipe_id"]));

  $response = $user->clearSavedRecipe($userID, $recipeID);
} else if($_SERVER["REQUEST_METHOD"] == "GET") {
  $userID = intval(h($_GET["user_id"]));
  $response = $user->getSavedRecipes($userID);
} else {
  $response = array(
    'status'=>0,
    'status_message'=>'Invalid request method'
  ); 
}

header('Content-Type: application/json');
echo json_encode($response);

?>