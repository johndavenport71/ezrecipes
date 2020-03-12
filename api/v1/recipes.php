<?php
include('../../php/init.php');
include('../../Models/Recipe.php');
include('../../Models/User.php');

$controller = new Recipe($conn);
$userCon = new User($conn);
$request = $_SERVER["REQUEST_METHOD"];
if($request == "GET") {
  //get recipes
  if(isset($_GET["id"])) {
    $id = h($_GET["id"]);
    $recipe = $controller->getSingleRecipe($id);
    $response = array(
      'status' => 1,
      'status_message' => 'success',
      'recipe' => $recipe->jsonSerialize()
    );
  } else if(isset($_GET["user"])) {
    $user = h($_GET["user"]);
    $response = $controller->getRecipesByUser($user);
  } else {
    if(isset($_GET["limit"])) {
      $limit = h($_GET["limit"]);
    } else {
      $limit = 10;
    }
    $response = $controller->getAllRecipes($limit);
  }
} else if($request == "POST") {

  $userAuth = h($_POST["user_auth"]);

  if($userCon->userAuth($userAuth)) {
    //add new recipe
    if(isset($_POST["recipe_image"])) {
      $recipe["recipe_img"] = $_POST["recipe_image"];
    } else {
      $recipe["recipe_img"] = "";
    }
  
    $recipe["recipe_title"] = h($_POST["recipe_title"]);
    $recipe["recipe_desc"] = h($_POST["recipe_desc"]);
    $recipe["fat"] = h((int)$_POST["fat"]);
    $recipe["calories"] = h((int)$_POST["calories"]);
    $recipe["protein"] = h((int)$_POST["protein"]);
    $recipe["sodium"] = h((int)$_POST["sodium"]);
    $recipe["directions"] = h($_POST["directions"]);
    $recipe["user_id"] = $_POST["user_id"] ? h((int)$_POST["user_id"]) : 0;
  
    $recipe["directions"] = nl_dbl_slash(h($recipe["directions"]));
  
    $allIngredients = h($_POST["all_ingredients"]);
    $allIngredients = str_to_array_dbl_slash($allIngredients);
  
    $categories = nl_dbl_slash(h($_POST["categories"]));
    $categories = str_to_array_dbl_slash($categories);
  
    $response = $controller->addRecipe($recipe, $allIngredients, $categories);

  } else {
    $response = array(
      'status' => 0,
      'status_message' => 'Failed to authenticate user, try logging in again and resubmitting'
    );
  }

} else if($request == "DELETE") {
  //delete recipe
  if(isset($_GET["recipeID"]) && isset($_GET["userID"])) {
    $recipe = h($_GET["recipeID"]);
    $user = h($_GET["userID"]);
    $response = $controller->deleteRecipe($recipe, $user);
  } else {
    $response = array(
      'status' => 0,
      'status_message' => 'recipe and user ids are required to delete a recipe'
    );
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