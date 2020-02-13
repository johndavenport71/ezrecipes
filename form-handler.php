<?php 
include('partials/head.php');

$user_id = 1;

$recipe["recipe_title"] = $_POST["recipe_title"];
$recipe["recipe_desc"] = $_POST["description"];
$recipe["prep_time"] = $_POST["prep_time"];
$recipe["cook_time"] = $_POST["cook_time"];
$recipe["steps"] = $_POST["steps"];
$recipe["recipe_img"] = $_POST["image"] ?? "";
$recipe["user_id"] = $user_id;

$recipe["steps"] = nl_dbl_slash($recipe["steps"]);

//Will work for extracting steps from database
//preg_split("/\/\//", $stepsTest);

$ingredients = $_POST["all_ingredients"];

$preppedIngr = parseIngredients($ingredients);

//dd(array_values($preppedIngr));

$categories = prepArray($_POST["categories"]);

//dd($categories);



addRecipe($conn, $recipe, $preppedIngr, $categories);


/*************
 * 
 * DO something else here!!!! Duh......
 */