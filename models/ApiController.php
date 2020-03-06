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
  * Get user info from database
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getUser(int $id) {
    $stmt = $this->conn->prepare("SELECT first_name, last_name, email, display_name, member_level, profile_pic FROM users WHERE user_id = :id");
    if($stmt->execute([":id"=>$id])) {
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if($data) {
        $response = array(
          'status'=>1,
          'status_message'=>'success',
          'data'=>$data
        );
      } else {
        $response = array(
          'status'=>0,
          'status_message'=>'Failed to retreive record'
        );
      }
      
    }
    
    return $response;
  }//end getUser

  /**
  * Add a new user to the database
  *
  * @param 	 Array 	 $user
  * @return 	 Array
  */
  function addUser(Array $user) {
    $errors = $this->checkUser($user);

    if(!sizeof($errors)) {
      $stmt = $this->conn->prepare("INSERT INTO users VALUES (DEFAULT, :first, :last, :email, NULL, :user_auth, 'm', NULL, DEFAULT)");
      $stmt->bindParam(":first", $user["first_name"]);
      $stmt->bindParam(":last", $user["last_name"]);
      $stmt->bindParam(":email", $user["email"]);
      $stmt->bindParam(":user_auth", $user["user_auth"]);
      try {
        $stmt->execute();
        $id = intval($this->conn->lastInsertId());
      } catch(PDOException $e) {
        array_push($errors, $e->getMessage());
      }
    }

    if(sizeof($errors)) {
      $response = array(
        'status'=>0,
        'status_message'=>'failed to add new user',
        'errors'=>$errors
      );
    } else {
      $response = array(
        'status'=>1,
        'status_message'=>'Added new user',
        'new_user_id'=>$id
      );
    }

    return $response;
  }//end addUser

  /**
  * Delete a user but leave their recipes in database
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function deleteUser(int $id) {
    $errors = [];
    //update recipe ID's
    $stmt = $this->conn->prepare("UPDATE recipes SET user_id = 0 WHERE user_id = :id");
    try {
      $stmt->execute([':id' => $id]);
      $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = :id LIMIT 1");
      $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
      array_push($errors, "Failed to update user id for recipes with user id " . $id);
    }

    if(sizeof($errors)) {
      $response = array(
        'status' => 0,
        'status_message' => 'Failed to delete user',
        'errors' => $errors
      );
    } else {
      $response = array(
        'status' => 1,
        'status_message' => 'Successfully deleted user: ' . $id
      );
    }

    return $response;
  }

  /**
  * Check user values and return an array of errors
  *
  * @param 	 Array 	 $data
  * @return 	 Array
  */
  function checkUser(Array $data) {
    $errors = [];

    if(strlen($data["first_name"]) == 0 || strlen($data["last_name"]) == 0) {
      array_push($errors, "First and Last name are required");
    }
    if(strlen($data["email"]) == 0) {
      array_push($errors, "Email Address is required");
    }

    return $errors;
  }//end checkUser

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