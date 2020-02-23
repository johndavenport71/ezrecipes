<?php
include('../php/init.php');

if($_SERVER["REQUEST_METHOD"] == 'GET') {

  $params = explode(",", h($_GET["name"]));

  $stmt = $conn->prepare("SELECT category_name, rc.recipe_id FROM recipe_category rc INNER JOIN categories ON categories.category_id = rc.category_id WHERE category_name IN (". preciseImplode($params) .")");
  if($stmt->execute()) {
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
    $ids = array_unique($ids);
  }

  if(sizeof($ids)) {
    foreach($ids as $i=>$id) {
      $recipe = getFullRecipe($conn, $id);
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
  
  header('Content-Type: application/json');
  echo json_encode($response);
}

?>