<?php
include('../../php/init.php');
include(MODELS . '/ApiController.php');

$controller = new ApiController($conn);


header('Content-Type: application/json');
echo json_encode($response);

?>