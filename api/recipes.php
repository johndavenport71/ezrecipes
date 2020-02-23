<?php
include('../php/init.php');

if($_SERVER["REQUEST_METHOD"] == 'GET') {
  
  if(isset($_GET["id"])) {
    $id = h($_GET["id"]);

    $recipe = getFullRecipe($conn, $id);

    if(isset($recipe)) {
      $response = array("status"=>1, "recipe"=> $recipe->jsonSerialize());
      
    } else {
      $response = array('status'=>0, 'status_message'=>'Failed to retrieve record');
    }

  } else {
    if(isset($_GET["limit"])) {
      $limit = intval(h($_GET["limit"]));
    } else {
      $limit = 10;
    }

    $stmt = $conn->prepare("SELECT recipe_id FROM recipes ORDER BY recipe_id DESC LIMIT :limit");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    if($stmt->execute()) {
      $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
      foreach($ids as $i=>$id) {
        $recipe = getFullRecipe($conn, $id);
        $data[$i] = $recipe->jsonSerialize();
      }
    }

    if(isset($data)) {
      $response = array(
        'status'=>1,
        'status_message'=>'Success',
        'data'=>$data
      );
    } else {
      $response = array(
        'status'=>0,
        'status_message'=>'Failed to retrieve records'
      );
    }
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