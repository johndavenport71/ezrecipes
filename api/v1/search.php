<?php

include('../../php/init.php');
include('../../Models/Search.php');

$controller = new Search($conn);
$request = $_SERVER["REQUEST_METHOD"];

if($request != "GET" || !isset($_GET["search"])) {
  $response = array(
    'status' => 0,
    'status_message' => 'Invalid request method'
  );
} else {
  $params = h($_GET["search"]);
  $recipes = $controller->searchRecipes($params);
  $response = array(
    'status' => 1,
    'status_message' => 'success',
    'search' => $recipes
  );
}

header('Content-Type: application/json');
echo json_encode($response);

?>