<?php

class Rating {
  private $conn;

  public function __construct(PDO $conn) {
    $this->conn = $conn;
  }

  /**
  * Get rating
  *
  * @param 	 int 	 $recipe_id
  * @return 	 double
  */
  function getRating(int $recipe_id) {
    $rating = 0;
    $stmt = $this->conn->prepare("SELECT rating FROM ratings WHERE recipe_id = :recipe");
    $stmt->bindParam(':recipe', $recipe_id, PDO::PARAM_INT);
    if($stmt->execute()) {
      $allRatings = $stmt->fetchAll(PDO::FETCH_COLUMN);
      $total = 0;
      foreach($allRatings as $rating) {
        $total .= intval($rating);
      }
      if(sizeof($allRatings)) {
        $rating = $total / sizeof($allRatings);
      }
    }
    return $rating;
  }//end getRating

  /**
  * Add a rating
  *
  * @param 	 int 	 $rating
  * @param 	 int 	 $recipe_id
  * @param 	 int 	 $user_id
  * @return 	 Array
  */
  function addRating(int $rating, int $recipe_id, int $user_id) {
    $stmt = $this->conn->prepare('INSERT INTO ratings VALUES (:rating, :recipe, :user)');
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':recipe', $recipe_id, PDO::PARAM_INT);
    $stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
    if($stmt->execute()) {
      $response = array(
        'status' => 1,
        'status_message' => 'rating added'
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'something went wrong'
      );
    }
    return $response;
  }//end addRating

  /**
  * Update rating
  *
  * @param  int   $rating
  * @param  int   $recipe_id
  * @param  int   $user_id
  * @return Array
  */
  function updateRating(int $rating, int $recipe_id, int $user_id) {
    $stmt = $this->conn->prepare("UPDATE ratings SET rating = :rating WHERE recipe_id = :recipe AND user_id = :user LIMIT 1");
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':recipe', $recipe_id, PDO::PARAM_INT);
    $stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
    if($stmt->execute()) {
      $response = array(
        'status' => 1,
        'status_message' => 'rating updated'
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'rating not updated'
      );
    }
    return $response;
  }//end updateRating

}

?>