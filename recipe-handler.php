<?php 
include('php/init.php');
include('partials/head.php');

$user_id = 1;

$recipe["recipe_title"] = $_POST["recipe_title"];
$recipe["recipe_desc"] = $_POST["description"];
$recipe["prep_time"] = $_POST["prep_time"];
$recipe["cook_time"] = $_POST["cook_time"];
$recipe["steps"] = $_POST["steps"];
$recipe["recipe_img"] = $_FILES["recipe_image"]["name"] ?? "";
$recipe["user_id"] = $user_id;

$recipe["steps"] = nl_dbl_slash($recipe["steps"]);

//Will work for extracting steps from database
//preg_split("/\/\//", $stepsTest);

$ingredients = $_POST["all_ingredients"];

$preppedIngr = parseIngredients($ingredients);

$categories = prepArray($_POST["categories"]);

$recipeID = addRecipe($conn, $recipe, $preppedIngr, $categories);

if($recipe["recipe_img"]) {
  $target_dir = "uploads/recipes/". $recipeID ."/";
  if(!file_exists($target_dir)) {
    mkdir($target_dir);
  }
  $target_file = $target_dir . basename($_FILES["recipe_image"]["name"]);
  $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $uploadOK = 1;

  if($uploadOK === 0) {
    echo "File not uploaded";
  } else {
    if(move_uploaded_file($_FILES["recipe_image"]["tmp_name"], $target_file)) {
      echo "File uploaded successfully: " . basename($_FILES["recipe_image"]["name"]);
      $stmt = $conn->prepare("UPDATE recipes SET recipe_img = :img WHERE recipe_id = :id");
      $stmt->execute([":img"=>$target_file, ":id"=>$recipeID]);
    } else {
      echo "File not uploaded";
    }
  }
}

header('Location: show-recipe.php?id=' . $recipeID);


/*************
 * 
 * DO something else here!!!! Duh......
 */