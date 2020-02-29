<?php
include('../php/init.php');
include('../models/api-controller.obj.php');

$controller = new APIController($conn);

if($_SERVER["REQUEST_METHOD"] == 'GET') {
  
  if(isset($_GET["id"])) {
    $id = h($_GET["id"]);

    $recipe = $controller->getFullRecipe($id);

    if(isset($recipe)) {
      $response = array("status"=>1, "recipe"=> $recipe->jsonSerialize());
      
    } else {
      $response = array('status'=>0, 'status_message'=>'Failed to retrieve record');
    }

  } else if (isset($_GET["user"])) {
    $userID = h($_GET["user"]);

    $response = $controller->getRecipesByUser($userID);

  } else {
    if(isset($_GET["limit"])) {
      $limit = h($_GET["limit"]);
    } else {
      $limit = 10;
    }

    $response = $controller->getAllRecipes($limit);
  }

  header('Content-Type: application/json');
  echo json_encode($response);
}

if($_SERVER["REQUEST_METHOD"] == 'POST') {

  $recipe["recipe_title"] = $_POST["recipe_title"];
  $recipe["recipe_desc"] = $_POST["description"];
  $recipe["prep_time"] = $_POST["prep_time"];
  $recipe["cook_time"] = $_POST["cook_time"];
  $recipe["steps"] = $_POST["steps"];
  $recipe["recipe_img"] = $_FILES["recipe_image"]["name"] ?? "";
  $recipe["user_id"] = intval($_POST["user_id"]) ?? 1;

  $recipe["steps"] = nl_dbl_slash($recipe["steps"]);

  $ingredients = $_POST["all_ingredients"];

  $preppedIngr = parseIngredients($ingredients);

  $categories = prepArray($_POST["categories"]);

  $recipeID = addRecipe($conn, $recipe, $preppedIngr, $categories);

  if($recipeID) {
    $response = array(
      'status'=>1,
      'message'=>'Recipe added successfully',
      'recipe_id'=>$recipeID
    );
  }
  if($recipe["recipe_img"]) {
    $target_dir = SITE_ROOT . "/uploads/recipes/". $recipeID ."/";
    if(!file_exists($target_dir)) {
      mkdir($target_dir);
    }
    $target_file = $target_dir . basename($_FILES["recipe_image"]["name"]);
    $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOK = 1;

    if($uploadOK === 0) {
      $response["image_message"] = 'Failed to upload image';
    } else {
      if(move_uploaded_file($_FILES["recipe_image"]["tmp_name"], $target_file)) {
        $response["image_message"] =  "File uploaded successfully: " . basename($_FILES["recipe_image"]["name"]);
        $stmt = $conn->prepare("UPDATE recipes SET recipe_img = :img WHERE recipe_id = :id");
        $stmt->execute([":img"=>$target_file, ":id"=>$recipeID]);
      } else {
        $response["image_message"] = 'Failed to upload image';
      }
    }
  }

  header('Content-Type: application/json');
  echo json_encode($response);

}

?>