<?php

class User {

  private $conn;

  public function __construct(PDO $conn) {
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
          $token = generateToken();
          $expires = new DateTime("now", new DateTimeZone("EST"));
          $expires->add(new DateInterval('PT1H0M'));
          $this->insertToken($user["user_id"], $token, $expires);
          $response = array(
            'status'=>1,
            'status_message'=>'User successfully authenticated.',
            'user_id'=>$user["user_id"],
            'token'=>$token,
            'expires'=>$expires,
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
  * Add token to tokens table
  *
  * @param 	 String 	 $token
  * @return 	 void
  */
  function insertToken(int $id, String $token, Object $expires) {
    $stmt = $this->conn->prepare("INSERT INTO user_tokens VALUES (:user_id, :token, :expires)");
    $stmt->execute([':user_id' => $id, ':token' => $token, ':expires' => $expires->format('Y-m-d\TH:i:s.u')]);
  }

  /**
  * Check if a token has expired
  *
  * @param 	 int 	 $user_id
  * @return 	 Boolean
  */
  function checkToken(int $user_id) {
    $stmt = $this->conn->prepare("SELECT TIMEDIFF(expires, now()) FROM user_tokens WHERE user_id = :id");
    if($stmt->execute([':id' => $user_id])) {
      return $stmt->fetch(PDO::FETCH_COLUMN);
    } else {
      return false;
    }
  }

  /**
  * Delete expired token
  *
  * @return 	 Boolean
  */
  function deleteTokens() {
    $stmt = $this->conn->prepare("DELETE FROM user_tokens WHERE TIMEDIFF(expires, now()) <= 0");
    return $stmt->execute();
  }

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
  

}


?>