<?php

class Ingredient {
  private $conn;

  public function __construct(PDO $conn) {
    $this->conn = $conn;
  }

  /**
  * Get ingredients by recipe ID
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  public function getIngredientsByRecipe(int $id) {
    $sql = "SELECT i.ingredient_desc from ingredients i";
    $sql .= " INNER JOIN recipe_ingredients ri ON ri.ingredient_id = i.ingredient_id";
    $sql .= " WHERE ri.recipe_id = :recipe_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':recipe_id', $id);

    if($stmt->execute()) {
      return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
      return [];
    }
  }

  /**
  * Check and update ingredient fields where necessary
  *
  * @param 	 Array  $values
  * @return   void
  */
  public function updateIngredients(Array $values) {
  
    $existingFields = $this->getAllIngredients($this->conn);

    $newFields = array_diff($values, array_values($existingFields));
    if($newFields) {
      $stmt = $this->conn->prepare("INSERT INTO ingredients VALUES (NULL, :fieldName)");
      foreach($newFields as $new) {
        $stmt->execute([':fieldName'=>$new]);
      }
    }
  }//end updateIngredients

  /**
  * Get an associative array of all ingredients and their ids
  *
  * @return 	 Array
  */
  public function getAllIngredients() {
    $ingredients = $this->conn->query("SELECT * FROM ingredients")->fetchAll(PDO::FETCH_ASSOC);
    $array = [];

    foreach($ingredients as $ingr) {
      $array[intval($ingr["ingredient_id"])] = $ingr["ingredient_desc"];
    }

    return $array;
  }

  /**
  * Get all ids for associated ingredients in an array
  *
  * @param 	 Array 	 $ingredients
  * @return 	 Array
  */
  function searchIngredients(Array $ingredients) {
    $sql = "SELECT ingredient_id FROM ingredients WHERE ingredient_desc REGEXP ('";
    $sql .= rtrim(regexpImplode($ingredients), '|') . "')";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_COLUMN);
  }
}