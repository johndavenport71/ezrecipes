<?php

require_once('FullRecipe.php');

class Category {
  private $conn;

  public function __construct(PDO $conn) {
    $this->conn = $conn;
  }

  /**
  * Get categories by recipe ID
  *
  * @param 	 int 	 $id
  * @return 	 Array
  */
  public function getCategoriesByRecipe(int $id) {
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
  * Check and update category fields where necessary
  *
  * @param 	 Array  $values
  * @return   void
  */
  function updateCategories(Array $values) {
  
    $allCategories = $this->getAllCategories();
    $existingFields = $allCategories["categories"];
    $check = [];
    foreach($existingFields as $field) {
      array_push($check, $field["category_desc"]);
    }
    $newFields = array_diff($values, $check);

    if($newFields) {
      $stmt = $this->conn->prepare("INSERT INTO categories VALUES (DEFAULT, :fieldName)");
      foreach($newFields as $new) {
        $stmt->execute([':fieldName'=>$new]);
      }
    }
  }

  /**
  * Get an associative array of all categories and their ids
  *
  * @return 	 Array
  */
  function getAllCategories() {
    $categories = $this->conn->query("SELECT category_id, category_desc FROM categories ORDER BY category_desc ASC")->fetchAll(PDO::FETCH_ASSOC);
    $response = array(
      'status' => 1,
      'status_message' => 'success',
      'categories' => $categories
    );
    return $response;
  }

  /**
  * Get all ids for associated categories in an array
  *
  * @return 	 Array
  */
  function searchCategories(Array $categories) {
    $stmt = $this->conn->prepare("SELECT category_id FROM categories WHERE category_desc LIKE :desc LIMIT 1");
    $ids = [];
    foreach($categories as $category) {
      $desc = '%' . $category . '%';
      if($stmt->execute([':desc' => $desc])) {
        $id = $stmt->fetch(PDO::FETCH_COLUMN);
        array_push($ids, $id);
      }
    }
    return $ids;
  }

  /**
  * Get all recipes that match given categories
  *
  * @param 	 String 	 $params
  * @param 	 int 	     $limit
  * @return 	 Array
  */
  function getRecipesByCategory(String $params, int $limit = 25) {
    $sql = "SELECT category_desc, rc.recipe_id FROM recipe_categories rc ";
    $sql .= "INNER JOIN categories ON categories.category_id = rc.category_id WHERE category_desc IN (:params) LIMIT :limit";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':params', $params);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    if($stmt->execute()) {
      $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
      $ids = array_unique($ids);
    }

    if(sizeof($ids)) {
      foreach($ids as $i=>$id) {
        $recipe = new FullRecipe($this->conn, $id);
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
        'status_message'=>'Failed to find',
        'params' => $params
      );
    }

    return $response;
  }//end getRecipesByCategory
}