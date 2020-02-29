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
    if(strlen($data["password"]) == 0 || strlen($data["password_confirm"]) == 0) {
      array_push($errors, "Password is required");
    } else if($data["password"] != $data["password_confirm"]) {
      array_push($errors, "Passwords must match");
    }

    return $errors;

  }//end checkUser

  /**
  * Validate user credentials for login
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
  } 

  /**
  * Add new user to database
  *
  * @param 	 Array 	 $values
  * @return   Array
  */
  function addUser(Array $values) {
    $errors = $this->checkUser($values);

    if(!sizeof($errors)) {
      $values["pass_hash"] = password_hash($values["password"], PASSWORD_DEFAULT);
      $stmt = $this->conn->prepare("INSERT INTO users VALUES (NULL, :first, :last, NULL, 'm', :password, NULL, :email)");
      $stmt->bindParam(":first", $values["first_name"]);
      $stmt->bindParam(":last", $values["last_name"]);
      $stmt->bindParam(":password", $values["pass_hash"]);
      $stmt->bindParam(":email", $values["email"]);
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
  * Get single user by ID
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getSingleUser(int $id) {
    $stmt = $this->conn->prepare("SELECT first_name, last_name, display_name, profile_pic FROM users WHERE user_id = :id");
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
    
  }//end getSingleUser

  /**
  * Get all recipes with user id
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getRecipesByUser(int $id) {
    $stmt = $this->conn->prepare("SELECT recipe_id FROM recipes WHERE user_id = :id");
    if($stmt->execute([":id" => $id])) {
      $recipeIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);
      $recipeIDs = array_unique($recipeIDs);

      foreach($recipeIDs as $i=>$rID) {
        $recipe = $this->getFullRecipe($rID);
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
  }

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
      $stmt = $this->conn->prepare("DELETE FROM recipe_category WHERE recipe_id = :id");
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

  /**
  * Get all recipes that match given categories
  *
  * @param 	 Array 	 $params
  * @return 	 Array
  */
  function getRecipesByCategory(Array $params) {
    $stmt = $this->conn->prepare("SELECT category_name, rc.recipe_id FROM recipe_category rc INNER JOIN categories ON categories.category_id = rc.category_id WHERE category_name IN (". preciseImplode($params) .")");
  if($stmt->execute()) {
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
    $ids = array_unique($ids);
  }

  if(sizeof($ids)) {
    foreach($ids as $i=>$id) {
      $recipe = getFullRecipe($this->conn, $id);
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
  * @return 	 Array
  */
  function getFullRecipe(int $id) {
    $recipe = $this->getRecipe($id);
    $ingredients = $this->getIngredients($id);
    $categories = $this->getCategories($id);
    return new Recipe($recipe, $ingredients, $categories, 0);
  }//end getFullRecipe

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
        $recipe = $this->getFullRecipe($id);
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
  * Get all columns for one recipe
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getRecipe(int $id) {
    $sql = "SELECT * FROM recipes WHERE recipe_id = :id LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    if($stmt->execute()) {
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      return [];
    }
  }//end getRecipe

  /**
  * Get a recipe's categories
  *	 
  * @param 	 int  $id 	 
  * @return  Array
  */
  function getCategories(int $id) {
    $sql = "SELECT category_name FROM categories c";
    $sql .= " INNER JOIN recipe_category rc ON c.category_id = rc.category_id";
    $sql .= " WHERE rc.recipe_id = :recipe_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':recipe_id', $id);

    if($stmt->execute()) {
      return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
      return [];
    }
  }//end getCategories

  /**
  * Get a recipe's ingredients and amounts
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getIngredients(int $id) {
    $sql = "SELECT i.ingredient_name, a.amount_desc from ingredients i";
    $sql .= " INNER JOIN recipe_ingredients ri ON ri.ingredient_id = i.ingredient_id";
    $sql .= " INNER JOIN amounts a ON ri.amount_id = a.amount_id";
    $sql .= " WHERE ri.recipe_id = :recipe_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':recipe_id', $id);

    if($stmt->execute()) {
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
      return [];
    }
  }//end getIngredients

}
?>