<?php
include_once('../../php/init.php');
include_once('../../Models/Recipe.php');
include_once('../../Models/User.php');

$controller = new Recipe($conn);
$userCon = new User($conn);
$request = $_SERVER["REQUEST_METHOD"];

if($request == "GET") {
  //get recipes
  if(isset($_GET["id"])) {
    $params = $_GET["id"];

    if(is_array($params)) {
      $recipes = [];
      foreach($params as $id) {
        $recipe = $controller->getSingleRecipe(h($id));
        if($recipe->getID() != 0) {
          array_push($recipes, $recipe->jsonSerialize());
        }
      }
      if(sizeof($recipes)) {
        $response = array(
          'status' => 1,
          'status_message' => 'success',
          'array' => true,
          'recipes' => $recipes
        );
      } else {
        $response = array(
          'status' => 0,
          'status_message' => 'no recipes found'
        );
      }
    } else {
      $recipe = $controller->getSingleRecipe(h($params));
      if($recipe->getID() == 0) {
        $response = array(
          'status' => 0,
          'status_message' => 'recipe does not exist'
        );
      } else {
        $response = array(
          'status' => 1,
          'status_message' => 'success',
          'array' => false,
          'recipe' => $recipe->jsonSerialize()
        );
      }
    }
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
  //add new recipe
  $recipe["recipe_title"] = h($_POST["recipe_title"]);
  $recipe["recipe_desc"] = h($_POST["recipe_desc"]);
  $recipe["fat"] = h((int)$_POST["fat"]);
  $recipe["calories"] = h((int)$_POST["calories"]);
  $recipe["protein"] = h((int)$_POST["protein"]);
  $recipe["sodium"] = h((int)$_POST["sodium"]);
  $recipe["directions"] = h($_POST["directions"]);
  $recipe["user_id"] = $_POST["user_id"] ? h((int)$_POST["user_id"]) : 0;
  $recipe["recipe_img"] = "";
  $recipe["directions"] = nl_dbl_slash(h($recipe["directions"]));

  $allIngredients = h($_POST["all_ingredients"]);
  $allIngredients = str_to_array_dbl_slash($allIngredients);

  $categories = nl_dbl_slash(h($_POST["categories"]));
  $categories = str_to_array_dbl_slash($categories);

  $response = $controller->addRecipe($recipe, $allIngredients, $categories);

  if(isset($_FILES["image"])) {
    $target_dir = SITE_ROOT . "/uploads/recipes/". $response["recipe_id"] ."/";
    $recipe["recipe_img"] = "https://www.jdavenportoti.com/ezrecipes/uploads/recipes/". $response["recipe_id"] ."/" . $_FILES["image"]["name"];
    if(!file_exists($target_dir)) {
      mkdir($target_dir);
    }
    $target_file = $target_dir . $_FILES["image"]["name"];
    $uploadOK = 1;
    
    if($uploadOK === 0) {
      $response["image_message"] = 'Failed to upload image';
    } else {
      if(move_uploaded_file($_FILES["image"]['tmp_name'], $target_file)) {
        $uploadOK = 1;
        $stmt = $conn->prepare("UPDATE recipes SET recipe_image = :img WHERE recipe_id = :id");
        $stmt->execute([":img"=>$recipe["recipe_img"], ":id"=>$response["recipe_id"]]);
      } else {
        $uploadOK = 0;
      }
    }
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
      'status_message' => 'recipe and user ids are required to delete a recipe: [recipeID] [userID]'
    );
  }
} else if($request == "PUT") {
  $data = parsePut();
  $recipe["recipe_id"] = h($data["recipe_id"]);
  $recipe["recipe_title"] = h($data["recipe_title"]);
  $recipe["recipe_desc"] = h($data["recipe_desc"]);
  $recipe["fat"] = h((int)$data["fat"]);
  $recipe["calories"] = h((int)$data["calories"]);
  $recipe["protein"] = h((int)$data["protein"]);
  $recipe["sodium"] = h((int)$data["sodium"]);
  $recipe["directions"] = h($data["directions"]);
  $recipe["user_id"] = $data["user_id"] ? h((int)$data["user_id"]) : 0;
  
  $recipe["directions"] = nl_dbl_slash(h($recipe["directions"]));
  
  $allIngredients = h($data["all_ingredients"]);
  $allIngredients = str_to_array_dbl_slash($allIngredients);
  
  $categories = nl_dbl_slash(h($data["categories"]));
  $categories = str_to_array_dbl_slash($categories);
  
  if(isset($data["file"])) {
    $target_dir = SITE_ROOT . "/uploads/recipes/". $recipe["recipe_id"] ."/";
    if(!file_exists($target_dir)) {
      mkdir($target_dir);
    }
    $target_file = $target_dir . $data["file"]["file_name"];
    file_put_contents($target_file, $data["file"]["body"]);
    $uploadOK = 1;
    
    if($uploadOK === 0) {
      $response["image_message"] = 'Failed to upload image';
    } else {
      if(move_uploaded_file($data["file"]['file_name'], $target_file)) {
        $uploadOK = 1;
        $stmt = $conn->prepare("UPDATE recipes SET recipe_image = :img WHERE recipe_id = :id");
        $stmt->execute([":img"=>$recipe["recipe_img"], ":id"=>$recipe["recipe_id"]]);
      } else {
        $uploadOK = 0;
      }
    }
    $recipe["recipe_img"] = substr($target_file, strpos($target_file, "uploads"));
  } elseif(isset($data["recipe_img"])) {
    $recipe["recipe_img"] = $data["recipe_img"];
  } else {
    $recipe["recipe_img"] = "";
  }

  $response = $controller->updateRecipe($recipe, $allIngredients, $categories);
  if($uploadOK === 0) {
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