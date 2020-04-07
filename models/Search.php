<?php

require_once('FullRecipe.php');

class Search {
  private $conn;

  /**
  * constructor
  *
  * @param 	 PDO 	 $conn
  * @return 	 Object
  */
  function __construct(PDO $conn) {
    $this->conn = $conn;
  }

  /**
  * Search recipes and categories by query string
  *
  * @param 	 String 	 $query
  * @return 	 Array
  */
  function searchRecipes(String $query) {
    $query = "%" . $query . "%";
    $recipeIDs = [];
    $stmt = $this->conn->prepare("SELECT recipe_id FROM recipes WHERE recipe_title LIKE :query");
    $stmt->bindParam(':query', $query);
    if($stmt->execute()) {
      $recipeIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    $stmt = $this->conn->prepare("SELECT recipe_id FROM recipe_categories WHERE category_id IN (SELECT category_id FROM categories WHERE category_desc LIKE :query)");
    $stmt->bindParam(':query', $query);
    if($stmt->execute()) {
      $recipeIDs = array_merge($recipeIDs, $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    $recipeIDs = array_unique($recipeIDs);
    $recipes = [];
    foreach($recipeIDs as $id) {
      $row = new FullRecipe($this->conn, $id);
      array_push($recipes, $row->jsonSerialize());
    }

    $response = array(
      'status' => 1,
      'status_message' => 'success',
      'recipes' => $recipes
    );

    return $response;
  }// end searchRecipes

}

?>