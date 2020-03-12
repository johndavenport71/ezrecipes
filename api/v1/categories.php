<?php
include('../../php/init.php');
include('../../Models/Category.php');

$category = new Category($conn);

if($_SERVER["REQUEST_METHOD"] == "GET") {
  $params = $_GET["categories"];
  $params = urldecode($params);
  $limit = 25;
  if(isset($_GET["limit"])) {
    $limit = h($_GET["limit"]);
  }
  
  $response = $category->getRecipesByCategory($params, $limit);

} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method',
    'params' => $params
  );
}


header('Content-Type: application/json');
echo json_encode($response);

?>