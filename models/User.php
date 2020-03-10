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
  public function userAuth(String $email, String $pass) {
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
  }//end userAuth

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
  

}


?>