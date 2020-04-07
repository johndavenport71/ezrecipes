<?php 

require_once('FullRecipe.php');
require_once('Ingredient.php');
require_once('Category.php');

class Recipe {
  private $conn;

  public function __construct(PDO $conn) {
    $this->conn = $conn;
  }

  /**
  * Get all columns from recipes table
  *
  * @param  int $id
  * @return Array
  */
  public function getRecipeData(int $id) {
    $sql = "SELECT * FROM recipes WHERE recipe_id = :id LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    if($stmt->execute()) {
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      return [];
    }
  }

  /**
  * Get a single recipe by ID
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getSingleRecipe(int $id) {
    return new FullRecipe($this->conn, $id);
  }

  /**
  * Get all recipes up to a limit
  *
  * @param 	 int 	 $limit
  * @return 	 Array
  */
  function getAllRecipes(int $limit) {
    $stmt = $this->conn->prepare("SELECT recipe_id FROM recipes ORDER BY recipe_id DESC LIMIT :limit");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    if($stmt->execute()) {
      $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
      foreach($ids as $i=>$id) {
        $recipe = new FullRecipe($this->conn, $id);
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

    return $response;
  }//end getAllRecipes

  /**
  * Get all recipes with user id
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getRecipesByUser(int $id) {
    $stmt = $this->conn->prepare("SELECT recipe_id FROM recipes WHERE user_id = :id LIMIT 20");
    if($stmt->execute([":id" => $id])) {
      $recipeIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);
      $recipeIDs = array_unique($recipeIDs);

      foreach($recipeIDs as $i=>$rID) {
        $recipe = new FullRecipe($this->conn, $rID);
        $data[$i] = $recipe->jsonSerialize();
      }

      $response = array(
        'status'=>1,
        'status_message'=>'Success',
        'data'=>$data
      );

    } else {
      $response = array(
        'status'=>0,
        'status_message'=>'Failed to find recipes by user'
      );
    }

    return $response;
  }//end getRecipesByUser

  /**
  * Add a new recipe
  *
  * @param 	 Array 	 $data
  * @return 	 Array
  */
  function addRecipe(Array $recipe, Array $ingredients, Array $categories) {

    $recipeID = $this->insertRecipe($recipe, $ingredients, $categories);

    if(isset($recipeID)) {
      $response = array(
        'status'=>1,
        'message'=>'Recipe added successfully',
        'recipe_id'=>$recipeID
      );
    } else {
      $response = array(
        'status'=>0,
        'status_message'=>'Something went wrong, failed to add recipe'
      );
    }

    return $response;
  }

  /**
  * Insert a new recipe into the database
  *
  * @param 	 Array 	$data
  * @param 	 Array 	$ingredients
  * @param 	 Array 	$categories
  * @return  int
  */
  function insertRecipe(Array $data, Array $ingredients, Array $categories) {

    $sql = "INSERT INTO recipes VALUES (NULL, :title, :recipe_desc, :fat, :calories, :protein, :sodium, :directions, DEFAULT, :recipe_img, :user_id)";
    $recipeSQL = $this->conn->prepare($sql);
    $recipeSQL->bindParam(':title', $data['recipe_title']);
    $recipeSQL->bindParam(':recipe_desc', $data['recipe_desc']);
    $recipeSQL->bindParam(':fat', $data['fat'], PDO::PARAM_INT);
    $recipeSQL->bindParam(':calories', $data['calories'], PDO::PARAM_INT);
    $recipeSQL->bindParam(':protein', $data['protein'], PDO::PARAM_INT);
    $recipeSQL->bindParam(':sodium', $data['sodium'], PDO::PARAM_INT);
    $recipeSQL->bindParam(':directions', $data['directions']);
    $recipeSQL->bindParam(':recipe_img', $data['recipe_img']);
    $recipeSQL->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);

    try {
      $recipeSQL->execute();
      $recipeID = intval($this->conn->lastInsertId());

      $I = new Ingredient($this->conn);
      $C = new Category($this->conn);

      $I->updateIngredients($ingredients);
      $C->updateCategories($categories);

      $newIngredientIDs = $I->searchIngredients($ingredients);
      $newCategoryIDs = $C->searchCategories($categories);

      //add new rows to recipe_ingredients for each ingredient in the user ingredients array, with the recipe id
      $stmt = $this->conn->prepare("INSERT INTO recipe_ingredients (recipe_id, ingredient_id) VALUES (:recipe, :ingredient)");
      foreach($newIngredientIDs as $ing) {
        $stmt->execute([':recipe' => $recipeID, ':ingredient' => $ing]);
      }
    
      //add new rows to recipe_category for each category in the user categories array, with the recipe id
      $stmt = $this->conn->prepare("INSERT INTO recipe_categories (recipe_id, category_id) VALUES (:id, :catID)");
      foreach($newCategoryIDs as $cat) {
        $stmt->execute([':id'=>$recipeID, ':catID'=>$cat]);
      }

      //add row to ratings table
      $stmt = $this->conn->prepare("INSERT INTO ratings VALUES (0, :recipe, :user)");
      $stmt->execute([':recipe' => $recipeID, ':user' => $data["user_id"]]);
        
    } catch (PDOException $e) {
      print('Something went wrong: ' . $e->getMessage());
      die();
    }
    
    return $recipeID;

  }//end insertRecipe

  /**
  * Update a recipe
  *
  * @param 	 Array 	 $data
  * @param 	 Array 	 $ingredients
  * @param 	 Array 	 $categories
  * @return 	 Array
  */
  function updateRecipe(Array $data, Array $ingredients, Array $categories) {
    $sql = "UPDATE recipes SET recipe_title=:title, recipe_desc=:recipe_desc, fat=:fat, calories=:calories, protein=:protein, sodium=:sodium, directions=:directions,  recipe_image=:recipe_img WHERE recipe_id=:id";
    $recipeSQL = $this->conn->prepare($sql);
    $recipeSQL->bindParam(':id', $data['recipe_id']);
    $recipeSQL->bindParam(':title', $data['recipe_title']);
    $recipeSQL->bindParam(':recipe_desc', $data['recipe_desc']);
    $recipeSQL->bindParam(':fat', $data['fat'], PDO::PARAM_INT);
    $recipeSQL->bindParam(':calories', $data['calories'], PDO::PARAM_INT);
    $recipeSQL->bindParam(':protein', $data['protein'], PDO::PARAM_INT);
    $recipeSQL->bindParam(':sodium', $data['sodium'], PDO::PARAM_INT);
    $recipeSQL->bindParam(':directions', $data['directions']);
    $recipeSQL->bindParam(':recipe_img', $data['recipe_img']);

    try {
      $recipeSQL->execute();
      $recipeID = intval($data["recipe_id"]);

      $I = new Ingredient($this->conn);
      $C = new Category($this->conn);

      $I->updateIngredients($ingredients);
      $C->updateCategories($categories);

      $newIngredientIDs = $I->searchIngredients($ingredients);
      $newCategoryIDs = $C->searchCategories($categories);

      //add new rows to recipe_ingredients for each ingredient in the user ingredients array, with the recipe id
      $stmt = $this->conn->prepare("REPLACE INTO recipe_ingredients (recipe_id, ingredient_id) VALUES (:recipe, :ingredient)");
      foreach($newIngredientIDs as $ing) {
        $stmt->execute([':recipe' => $recipeID, ':ingredient' => $ing]);
      }
    
      //add new rows to recipe_category for each category in the user categories array, with the recipe id
      $stmt = $this->conn->prepare("REPLACE INTO recipe_categories (recipe_id, category_id) VALUES (:id, :catID)");
      foreach($newCategoryIDs as $cat) {
        $stmt->execute([':id'=>$recipeID, ':catID'=>$cat]);
      }

      $response = array(
        'status' => 1,
        'status_message' => 'successfully updated rows',
        'recipe_id' => $recipeID
      );

    } catch (PDOException $e) {
      $response = array(
        'status' => 0,
        'status_message' => 'failed to update rows',
        'errors' => $e->getMessage()
      );
    }
    
    return $response;
  }// end updateRecipe

  /**
  * Delete a recipe from the database
  *
  * @param 	 int 	 $recipeID
  * @param 	 int 	 $userID
  * @return 	 Array
  */
  function deleteRecipe(int $recipeID, int $userID) {
    $stmt = $this->conn->prepare("SELECT recipe_id FROM recipes WHERE user_id = :u_id AND recipe_id = :r_id");
    $stmt->execute([":u_id"=>$userID, ":r_id"=>$recipeID]);
    $isOwner = $stmt->fetchColumn();

    if($isOwner != false) {

      //delete from recipe_ingredients
      $stmt = $this->conn->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = :id");
      $stmt->execute([":id"=>$recipeID]);

      //delete from recipe_category
      $stmt = $this->conn->prepare("DELETE FROM recipe_categories WHERE recipe_id = :id");
      $stmt->execute([":id"=>$recipeID]);

      //delete from ratings
      $stmt = $this->conn->prepare("DELETE FROM ratings WHERE recipe_id = :id");
      $stmt->execute([":id"=>$recipeID]);

      //delete recipe
      $stmt = $this->conn->prepare("DELETE FROM recipes WHERE recipe_id = :id");
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

    return $response;
  }//end deleteRecipe

}



?>