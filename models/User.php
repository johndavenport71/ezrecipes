<?php

require_once('FullRecipe.php');

class User {

  private $conn;

  public function __construct(PDO $conn) {
    $this->conn = $conn;
  }

  /**
  * Check if email exists in db
  *
  * @param 	 String 	 $email
  * @return 	 Array
  */
  function checkEmail(String $email) {
    $stmt = $this->conn->prepare("SELECT email from users WHERE email LIKE :email");
    if($stmt->execute([':email' => $email])) {
      $response = array(
        'status' => 1,
        'status_message' => 'success',
        'email' => $stmt->fetch(PDO::FETCH_COLUMN)
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'something went wrong'
      );
    }

    return $response;
  }//end checkEmail

  /**
  * Login a user from email and password
  *
  * @param 	 String 	 $email
  * @param 	 String 	 $pass
  * @return 	 Array
  */
  public function userLogin(String $email, String $pass) {
    if(empty($email) || empty($pass)) {
      $response = array(
        'status'=>0,
        'status_message'=>'Missing required fields'
      );
    } else { 
      $stmt = $this->conn->prepare("SELECT user_id, uuid, email, user_auth FROM users WHERE email = :email");
      if($stmt->execute([":email" => $email])) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
        if($user && password_verify($pass, $user["user_auth"])) {
          $data = $this->getUser($user["user_id"]);
          $response = array(
            'status' => 1,
            'status_message' => 'User successfully authenticated.',
            'user' => $data,
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
  }//end userLogin

  /**
  * Authenticate user from uuid
  *
  * @param 	 String 	 $uuid
  * @return 	 Boolean
  */
  function userAuth(String $uuid) {
    $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE uuid = :uuid LIMIT 1");
    if($stmt->execute([':uuid' => $uuid])) {
      $result = $stmt->fetch(PDO::FETCH_COLUMN);
    }
    return($result != false);
  }

  /**
  * Change user password
  *
  * @param 	 Array 	 $data
  * @return 	 Array
  */
  function changePassword(Array $data) {
    if($this->checkPasswordChange($data)) {
      $hash = password_hash($data["new_password"], PASSWORD_DEFAULT);
      $stmt = $this->conn->prepare("UPDATE users SET user_auth = :pwd WHERE user_id = :id");
      $stmt->bindParam(':pwd', $hash);
      $stmt->bindParam(':id', $data["user_id"], PDO::PARAM_INT);
      if($stmt->execute()) {
        $response = array(
          'status' => 1,
          'status_message' => 'password changed'
        );
      } else {
        $response = array(
          'status' => 0,
          'status_message' => 'failed to update password'
        );
      }
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'missing required fields'
      );
    }
    return $response;
  }//end changePassword

  /**
  * Send email to reset password
  *
  * @param 	 String 	 $email
  * @return 	 Array
  */
  function requestReset(String $email) {
    $response = [];
    $stmt = $this->conn->prepare("SELECT user_id, first_name, last_name FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(":email", $email);
    if($stmt->execute()) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if(isset($user["user_id"])) {
        $selector = bin2hex(random_bytes(8));
        $token = random_bytes(32);
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        $url = "https://ezrecipes.fit/reset-password/" . $selector . "/" . bin2hex($token);
        $expires = date("U") + 900;

        $stmt = $this->conn->prepare("DELETE FROM pwd_reset WHERE email = :email");
        $stmt->execute([":email" => $email]);

        $stmt = $this->conn->prepare("INSERT INTO pwd_reset VALUES (DEFAULT, :email, :selector, :token, :expires)");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":selector", $selector);
        $stmt->bindParam(":token", $hashedToken);
        $stmt->bindParam(":expires", $expires);
        if($stmt->execute()) {
          $to = $email;
          $subject = 'EZRecipes Password Reset';

          $message = '<p>We recieved your password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email.</p>';
          $message .= '<p>Reset your password: <br>';
          $message .= '<a href="' . $url . '">' . $url . '</a></p>';

          $headers = "From: EZRecipes <jonathandavenport@students.abtech.edu>\r\n";
          $headers .= "Reply-To: jonathandavenport@students.abtech.edu\r\n";
          $headers .= "Content-type: text/html\r\n";

          $emailSent = mail($to, $subject, $message, $headers);
          if($emailSent == true) {
            $response = array(
              'status' => 1,
              'status_message' => 'email sent'
            );
          } else {
            $response = array(
              'status' => 0,
              'status_message' => 'email not sent'
            );
          }
        }
      } else {
        $response = array(
          'status' => 0,
          'status_message' => 'user not found'
        );
      }
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'something went wrong'
      );
    }
    return $response;
  }//end requestReset

  /**
  * Reset user password
  *
  * @param 	 Array 	 $data
  * @return 	 Array
  */
  function resetPassword(Array $data) {
    if($this->checkPasswordReset($data)) {
      $currentDate = date("U");
      $stmt = $this->conn->prepare("SELECT * FROM pwd_reset WHERE selector = :selector AND expires >= :date");
      $stmt->bindParam(":selector", $data["selector"]);
      $stmt->bindParam(":date", $currentDate);
      if($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $tokenBin = hex2bin($data["token"]);
        $tokenCheck = password_verify($tokenBin, $row["token"]);
        if($tokenCheck === false) {
          $response = array(
            'status' => 0,
            'status_message' => 'Could not validate request. Please try again.'
          );
        } elseif($tokenCheck === true) {
          $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
          $stmt->execute([":email" => $row["email"]]);
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
          $newPassword = password_hash($data["password"], PASSWORD_DEFAULT);
          $stmt = $this->conn->prepare("UPDATE users SET user_auth = :newPwd WHERE email = :email");
          $stmt->bindParam(":newPwd", $newPassword);
          $stmt->bindParam(":email", $user["email"]);
          if($stmt->execute()) {
            $passwordReset = true;
          } else {
            $passwordReset = false;
          }
          if($passwordReset === true) {
            $stmt = $this->conn->prepare("DELETE FROM pwd_reset WHERE email = :email");
            $stmt->execute([":email" => $user["email"]]);
          }
        }
      }
      if(isset($passwordReset) && $passwordReset === true) {
        $response = array(
          'status' => 1,
          'status_message' => 'password successfully reset'
        );
      } else {
        $response = array(
          'status' => 0,
          'status_message' => 'failed to reset password'
        );
      }
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'missing require parameters'
      );
    }
    return $response;
  }//end resetPassword

  /**
  * Get user info from database
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  public function getUser(int $id) {
    $stmt = $this->conn->prepare("SELECT user_id, uuid, first_name, last_name, email, display_name, member_level, profile_pic FROM users WHERE user_id = :id");
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
  * Get user info from database by uuid
  *
  * @param 	 String 	 $id
  * @return 	 Array
  */
  public function getUserByUUID(String $id) {
    $stmt = $this->conn->prepare("SELECT user_id, uuid, first_name, last_name, email, display_name, member_level, profile_pic FROM users WHERE uuid = :id");
    $stmt->bindParam(':id', $id);
    if($stmt->execute()) {
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
  }//end getUserByUUID

  /**
  * Add a new user to the database
  *
  * @param 	 Array 	 $user
  * @return 	 Array
  */
  public function addUser(Array $user) {
    $errors = $this->checkUser($user);

    if(!sizeof($errors)) {
      $stmt = $this->conn->prepare("INSERT INTO users VALUES (DEFAULT, :uuid, :first, :last, :email, NULL, :user_auth, 'm', NULL, DEFAULT)");
      $uuid = generateToken();
      $stmt->bindParam(":uuid", $uuid);
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

      $user = $this->getUser($id);
      $response = array(
        'status'=>1,
        'status_message'=>'Added new user',
        'user'=>$user
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
  public function deleteUser(int $id) {
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
  * Update user information
  *
  * @param 	 int 	 $id
  * @param 	 Array 	 $user
  * @return 	 Array
  */
  function updateUser(int $id, Array $user) {
    $errors = $this->checkUser($user);

    if($user["profile_pic"] == "") {
      $stmt = $this->conn->prepare("SELECT profile_pic FROM users WHERE user_id = :id LIMIT 1");
      if($stmt->execute([":id" => $id])) {
        $pic = $stmt->fetch(PDO::FETCH_COLUMN);
        $user["profile_pic"] = $pic;
      }
    }

    if(!sizeof($errors)) {
      $stmt = $this->conn->prepare("UPDATE users SET first_name = :first, last_name = :last, email = :email, display_name = :display,  profile_pic = :profile_pic WHERE user_id = :id");
      $stmt->bindParam(":id", $id, PDO::PARAM_INT);
      $stmt->bindParam(":first", $user["first_name"]);
      $stmt->bindParam(":last", $user["last_name"]);
      $stmt->bindParam(":email", $user["email"]);
      $stmt->bindParam(":display", $user["display_name"]);
      $stmt->bindParam(":profile_pic", $user["profile_pic"]);

      try {
        $stmt->execute();
      } catch(PDOException $e) {
        array_push($errors, $e->getMessage());
      }
    }

    if(sizeof($errors)) {
      $response = array(
        'status'=>0,
        'status_message'=>'failed to update user',
        'errors'=>$errors
      );
    } else {
      $user = $this->getUser($id);
      $response = array(
        'status'=>1,
        'status_message'=>'updated user information',
        'user'=>$user
      );
    }

    return $response;
  }//end updateUser

  /**
  * Check user values and return an array of errors
  *
  * @param 	 Array 	 $data
  * @return 	 Array
  */
  public function checkUser(Array $data) {
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
  * Check password change information
  *
  * @param 	 Array 	 $data
  * @return 	 Boolean
  */
  function checkPasswordChange(Array $data) {
    if(!isset($data["user_id"])) {
      return false;
    }
    if(!isset($data["og_password"])) {
      return false;
    }
    if(!isset($data["new_password"])) {
      return false;
    }
    if(!isset($data["new_password_confirm"])) {
      return false;
    }
    if($data["new_password"] != $data["new_password_confirm"]) {
      return false;
    }
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :id");
    $stmt->bindParam(':id', $data["user_id"], PDO::PARAM_INT);
    if($stmt->execute()) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if(!password_verify($data["og_password"], $user["user_auth"])) {
        return false;
      }
    }

    return true;
  }//end checkPasswordChange

  /**
  * Check required parameters for password reset
  *
  * @param 	 Array 	 $data
  * @return 	 Boolean
  */
  function checkPasswordReset(Array $data) {
    if(!isset($data["password"])) {
      return false; 
    }
    if(!isset($data["password_confirm"])) {
      return false; 
    }
    if(!isset($data["selector"])) {
      return false; 
    }
    if(!isset($data["token"])) {
      return false; 
    }
    return true;
  }//end checkPasswordReset

  /**
  * Save a recipe
  *
  * @param 	 int 	 $userID
  * @param 	 int 	 $recipeID
  * @return 	 Array
  */
  function saveRecipe(int $userID, int $recipeID) {
    $stmt = $this->conn->prepare("INSERT INTO saved_recipes VALUES (:user_id, :recipe_id)");
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->bindParam(':recipe_id', $recipeID, PDO::PARAM_INT);
    if($stmt->execute()) {
      $saved = $this->getSavedRecipes($userID);
      $response = array(
        'status' => 1,
        'status_message' => 'Recipe Saved',
        'saved_recipes' => $saved
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'Failed to save recipe'
      );
    }
    return $response;
  }// end saveRecipe

  /**
  * Clear saved recipe
  *
  * @param 	 int 	 $userID
  * @return 	 Array
  */
  function clearSavedRecipe(int $userID, int $recipeID) {
    $stmt = $this->conn->prepare("DELETE FROM saved_recipes WHERE user_id = :user_id AND recipe_id = :recipe_id LIMIT 1");
    $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
    $stmt->bindParam(':recipe_id', $recipeID, PDO::PARAM_INT);
    if($stmt->execute()) {
      $saved = $this->getSavedRecipes($userID);
      $response = array(
        'status' => 1,
        'status_message' => 'successfully removed saved recipe',
        'saved_recipes' => $saved
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'failed to remove saved recipe'
      );
    }
    return $response;
  }// end clearSavedRecipe

  /**
  * Get users saved recipes
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getSavedRecipes(int $id) {
    $stmt = $this->conn->prepare("SELECT recipe_id from saved_recipes WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
    if($stmt->execute()) {
      $recipeIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);
      $recipes = [];
      foreach($recipeIDs as $rID) {
        $row = new FullRecipe($this->conn, $rID);
        array_push($recipes, $row->jsonSerialize());
      }
      $response = array(
        'status' => 1,
        'status_message' => 'success',
        'recipes' => $recipes
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'something went wrong'
      );
    }
    return $response;
  }//end getSavedRecipes

}


?>