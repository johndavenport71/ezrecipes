<?php
include_once('../../php/init.php');
include_once('../../Models/Comment.php');

$commentCon = new Comment($conn);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  //todo comment submission
  $user = intval(h($_POST["user_id"]));
  $recipe = intval(h($_POST["recipe_id"]));
  $body = h($_POST["comment_body"]);

  $response = $commentCon->addComment($user, $recipe, $body);

} elseif($_SERVER["REQUEST_METHOD"] == "GET") {
  if(isset($_GET["recipe_id"])) {
    $recipe_id = intval(h($_GET["recipe_id"]));
    $response = $commentCon->getAllComments($recipe_id);
  }
} elseif($_SERVER["REQUEST_METHOD"] == "PUT") {
  $data = parsePut();
  $body = h($data["comment_body"]);
  $id = intval(h($data["comment_id"]));
  $response = $commentCon->updateComment($id, $body);
} elseif($_SERVER["REQUEST_METHOD"] == "DELETE") {
  if(isset($_GET["comment_id"])) {
    $id = intval(h($_GET["comment_id"]));
    $response = $commentCon->deleteComment($id);
  } else {
    $response = array(
      'status' => 0,
      'status_message' => 'missing parameters: expected [comment_id]'
    );
  }
} else {
  $response = array(
    'status' => 0,
    'status_message' => 'invalid request method'
  );
}


header('Content-Type: application/json');
echo json_encode($response);


?>