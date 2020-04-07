<?php

class Comment {
  private $conn;

  public function __construct(PDO $conn) {
    $this->conn = $conn;
  }

  /**
  * Add comment
  *
  * @param 	 int 	 $user_id
  * @param 	 int 	 $recipe_id
  * @param 	 String 	 $comment_body
  * @return 	 Array
  */
  function addComment(int $user_id, int $recipe_id, String $comment_body) {
    $stmt = $this->conn->prepare("INSERT INTO comments VALUES (DEFAULT, :comment, :user, :recipe, DEFAULT)");
    $stmt->bindParam(':comment', $comment_body);
    $stmt->bindParam(':user', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':recipe', $recipe_id, PDO::PARAM_INT);
    if($stmt->execute()) {
      $id = $this->conn->lastInsertId();
      $response = $this->getComment($id);
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'failed to add comment'
      );
    }

    return $response;
  }//end addComment

  /**
  * Update comment
  *
  * @param 	 int 	 $comment_id
  * @param 	 String 	 $body
  * @return 	 Array
  */
  function updateComment(int $comment_id, String $body) {
    $stmt = $this->conn->prepare("UPDATE comments SET comment_body = :body WHERE comment_id = :id LIMIT 1");
    $stmt->bindParam(':body', $body);
    $stmt->bindParam(':id', $comment_id, PDO::PARAM_INT);
    if($stmt->execute()) {
      $response = array(
        'status' => 1,
        'status_message' => 'success'
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'something went wrong'
      );
    }
    return $response;
  }//end updateComment

  /**
  * Get Comment
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function getComment(int $id) {
    $stmt = $this->conn->prepare("SELECT c.comment_id, c.comment_body, c.user_id, c.recipe_id, c.date_added, u.first_name, u.last_name, u.display_name, u.profile_pic FROM comments c INNER JOIN users u ON c.user_id = u.user_id WHERE c.comment_id = :comment_id LIMIT 1");
    $stmt->bindParam(':comment_id', $id, PDO::PARAM_INT);
    if($stmt->execute()) {
      $comment = $stmt->fetch(PDO::FETCH_ASSOC);
      $response = array(
        'status' => 1,
        'status_message' => 'success',
        'data' => $comment
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'failed to get comment'
      );
    }
    return $response;
  }//end getComment

  /**
  * Get all comments by recipe
  *
  * @param 	 int 	 $recipe_id
  * @return 	 Array
  */
  function getAllComments(int $recipe_id) {
    $stmt = $this->conn->prepare("SELECT c.comment_id, c.comment_body, c.user_id, c.recipe_id, c.date_added, u.first_name, u.last_name, u.display_name, u.profile_pic FROM comments c INNER JOIN users u ON c.user_id = u.user_id WHERE c.recipe_id = :recipe_id");
    $stmt->bindParam(':recipe_id', $recipe_id, PDO::PARAM_INT);
    if($stmt->execute()) {
      $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $response = array(
        'status' => 1,
        'status_message' => 'success',
        'data' => $comments
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'failed to get comments'
      );
    }
    return $response;
  }//end getAllComments

  /**
  * Delete comment
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  function deleteComment(int $id) {
    $stmt = $this->conn->prepare("DELETE FROM comments WHERE comment_id = :id LIMIT 1");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if($stmt->execute()) {
      $response = array(
        'status' => 1,
        'status_message' => 'Comment deleted'
      );
    } else {
      $response = array(
        'status' => 0,
        'status_message' => 'Failed to delete comment'
      );
    }
    return $response;
  }//end deleteComment
  

}

?>