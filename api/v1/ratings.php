<?php
include_once('../../php/init.php');
include_once('../../Models/Rating.php');

$ratingCon = new Rating($conn);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $rating = intval(h($_POST["rating"]));
  $recipe = intval(h($_POST["recipe_id"]));
  $user = intval(h($_POST["user_id"]));

  $response = $ratingCon->addRating($rating, $recipe, $user);

} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
  
} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method'
  );
}


header('Content-Type: application/json');
echo json_encode($response);


?>