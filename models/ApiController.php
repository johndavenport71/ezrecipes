<?php

class APIController {

  private $conn;

  /**
  * Constructor
  *
  * @param 	 PDO 	 $conn
  * @return 	 void
  */
  function __construct(PDO $conn) {
    $this->conn = $conn;
  }

  /**
  * validate user email and password
  *
  * @param 	 String 	 $email
  * @param 	 String 	 $pass
  * @return 	 Array
  */
  function userAuth(String $email, String $pass) {
    if(empty($email) || empty($pass)) {
      $response = array(
        'status'=>0,
        'status_message'=>'Missing required fields'
      );
    } else { 
      $stmt = $this->conn->prepare("SELECT user_id, email, user_auth FROM users WHERE email = :email");
      if($stmt->execute([":email" => $email])) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
        if($user && password_verify($pass, $user["user_auth"])) {
          $response = array(
            'status'=>1,
            'status_message'=>'User successfully authenticated.',
            'user_id'=>$user["user_id"]
          );
        } else {
          $response = array(
            'status'=>0,
            'status_message'=>'Could not authenticate user. Make sure that the email and password are correct.'
          );
        }        
      }
    }
    return $response;
  }//end userAuth

  /**
  * Get all recipes that match given categories
  *
  * @param 	 String 	 $params
  * @return 	 Array
  */
  function getRecipesByCategory(String $params) {
    $stmt = $this->conn->prepare("SELECT category_desc, rc.recipe_id FROM recipe_categories rc INNER JOIN categories ON categories.category_id = rc.category_id WHERE category_desc IN ('".$params."')");
    if($stmt->execute()) {
      $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
      $ids = array_unique($ids);
    }

    if(sizeof($ids)) {
      foreach($ids as $i=>$id) {
        $recipe = $this->getFullRecipe($id);
        $data[$i] = $recipe->jsonSerialize();
      }

      $response = array(
          'status'=>1,
          'status_message'=>'Success',
          'params'=>$params,
          'data'=>$data
        );
    } else {
      $response = array(
        'status'=>0,
        'status_message'=>'Failed to find'
      );
    }

    return $response;
  }//end getRecipesByCategory

  /**
  * Get a single recipe
  *
  * @param 	 int 	 $id
  * @return   Object
  */
  function getFullRecipe(int $id) {
    $recipe = $this->getRecipeData($id);
    $ingredients = $this->getIngredientsByRecipe($id);
    $categories = $this->getCategoriesByRecipe($id);
    return new Recipe($recipe, $ingredients, $categories, 0);
  }//end getFullRecipe

  /**
  * Get all columns from recipes table
  *
  * @param  int $id
  * @return Array
  */
  function getRecipeData(int $id) {
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
  * Get categories by recipe ID
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getCategoriesByRecipe(int $id) {
    $sql = "SELECT category_desc FROM categories c";
    $sql .= " INNER JOIN recipe_categories rc ON c.category_id = rc.category_id";
    $sql .= " WHERE rc.recipe_id = :recipe_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':recipe_id', $id);

    if($stmt->execute()) {
      return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
      return [];
    }
  }

  /**
  * Get ingredients by recipe ID
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getIngredientsByRecipe(int $id) {
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

}


?>