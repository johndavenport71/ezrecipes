<?php
include('../../php/init.php');
include('../../Models/Category.php');

$category = new Category($conn);

if($_SERVER["REQUEST_METHOD"] == "GET") {
  $params = queryStringRegexp(h($_GET["categories"]));
  
  $response = $category->getRecipesByCategory($params);

} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method'
  );
}


header('Content-Type: application/json');
echo json_encode($response);

?>