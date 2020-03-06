<?php
include('../../php/init.php');
include(MODELS . '/ApiController.php');

$controller = new ApiController($conn);
$request = $_SERVER["REQUEST_METHOD"];
if($request == "GET") {
  //get recipes
} else if($request == "POST") {
  //add new recipe
} else if($request == "DELETE") {
  //delete recipe
} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method'
  );
}


header('Content-Type: application/json');
echo json_encode($response);

?>