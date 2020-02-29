<?php
include('../php/init.php');
include('../models/api-controller.obj.php');

$controller = new APIController($conn);

if($_SERVER["REQUEST_METHOD"] == 'GET') {

  $params = explode(",", h($_GET["name"]));

  $response = $controller->getRecipesByCategory($params);
  
  header('Content-Type: application/json');
  echo json_encode($response);
}

?>