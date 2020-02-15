<?php

include('partials/head.php');
include('partials/main-header.php');

$userID = $_GET["id"];

$user = getSingleUser($conn, $userID);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $target_dir = "uploads/users/$userID/";
  $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
  $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $uploadOK = 1;

  if($_FILES["profile_pic"]["size"] > 500000) {
    echo "file too large";
    $uploadOK = 0;
  }

  if($uploadOK === 0) {
    echo "File not uploaded";
  } else {
    if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
      echo "File uploaded successfully: " . basename($_FILES["profile_pic"]["name"]);
    } else {
      echo "File not uploaded";
    }
  }

}

?>
  <ul>
    <li><a href="add-recipe.php">Add recipe</a></li>
    <li><a href="login.php">Login</a></li>
    <li><a href="sign-up.php">Sign up</a></li>
  </ul>
  <main>
    <h1>Single User page</h1>
    <p><?= $user["display_name"] ?></p>
    <image src="uploads/users/<?= $userID . "/" . $user["profile_pic"] ?>" width="50" height="50" >
    <form action="user-profile.php" method="post" enctype="multipart/form-data">
      <label for="profile_pic">Upload a picture</label>
      <input type="file" id="profile_pic" name="profile_pic"><br>
      <input type="submit" value="Upload">
    </form>
  </main>
 
<?php
include('partials/footer.php');
?>
