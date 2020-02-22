<?php
include('../php/init.php');
include(PARTIALS . '/head.php');
include(PARTIALS . '/main-header.php');

$userID = $_GET["id"];

$user = getSingleUser($conn, $userID);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $target_dir = WEB_ROOT . "uploads/users/$userID/";
  if(!file_exists($target_dir)) {
    mkdir($target_dir);
  }
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
      $stmt = $conn->prepare("UPDATE users SET profile_pic = :file_path WHERE user_id = :user_id");
      try {
        $stmt->execute([":file_path"=>$target_file, ":user_id"=>$userID]);
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    } else {
      echo "File not uploaded";
    }
  }

}

?>
  <ul>
    <li><a href="<?= WEB_ROOT ?>/views/add-recipe.php">Add recipe</a></li>
    <li><a href="<?= WEB_ROOT ?>/views/login.php">Login</a></li>
    <li><a href="<?= WEB_ROOT ?>/views/sign-up.php">Sign up</a></li>
  </ul>
  <main>
    <h1>Single User page</h1>
    <?php

      if(strlen($user["display_name"])) {
        echo '<p>'. $user["display_name"] .'</p>';
      } else {
        echo '<p>'. ucfirst($user["first_name"]) . " " . ucfirst($user["last_name"]) .'</p>';
      }

      if(strlen($user["profile_pic"])) {
        echo ('<image src="'. WEB_ROOT . "/" . $user["profile_pic"] .'" width="50" height="50" >');
      }
    ?>
    
    <form action="user-profile.php?id=<?= $userID ?>" method="post" enctype="multipart/form-data">
      <label for="display_name">Display Name:</label>
      <input type="text" id="display_name" name="display_name"><br>
      <label for="profile_pic" class="file-label">Drag a file here to upload a profile picture</label>
      <input type="file" id="profile_pic" name="profile_pic"><br>
      <input type="submit" value="Save Changes">
    </form>
  </main>
 
<?php
include(PARTIALS . '/footer.php');
?>
