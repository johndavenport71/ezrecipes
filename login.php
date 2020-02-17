<?php

include('partials/head.php');
include('partials/main-header.php');

$errors = [];
$hash;

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = h($_POST["email"]);
  $pass = h($_POST["password"]);

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");

  if($stmt->execute([":email"=>$email])) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
  } 
  
  if(is_null($user["user_auth"]) || !password_verify($pass, $user["user_auth"])) {
    array_push($errors, "Invalid username or password");
  } else {
    $_SESSION["is_logged_in"] = true;
    $_SESSION["user_name"] = $user["display_name"] ?? $user["first_name"];
  }

}

$email = $email ?? "";

?>
  <ul>
    <li><a href="add-recipe.php">Add recipe</a></li>
    <li><a href="login.php">Login</a></li>
    <li><a href="sign-up.php">Sign up</a></li>
  </ul>

  <main>
    <h1>Login</h1>
    <?php 
      if(sizeof($errors)) {
        echo ('<div class="errors">');
        echo '<p>'. $errors[0] .'</p>';
        echo ('</div>');
      }
    ?>

    <form action="login.php" method="post" class="user">
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required value="<?= $email ?>" placeholder="Email Address"><br>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required placeholder="Password"><br>
      <input type="submit" value="Login">
    </form>
  </main>
 
<?php
include('partials/footer.php');
?>
