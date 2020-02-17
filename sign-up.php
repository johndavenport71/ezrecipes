<?php

include('partials/head.php');
include('partials/main-header.php');

$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $values["first_name"] = h($_POST["first_name"]);
  $values["last_name"] = h($_POST["last_name"]);
  $values["email"] = h($_POST["email"]);
  $values["password"] = h($_POST["password"]);
  $values["password_confirm"] = h($_POST["password_confirm"]);

  if($values["password"] !== $values["password_confirm"]) {
    array_push($errors, 'Passwords do not match');
  } else {
    $values["pass_hash"] = password_hash($values["password"], PASSWORD_DEFAULT);
    $errors = addNewUser($conn, $values);
  }

}

$fname = $values["first_name"] ?? "";
$lname = $values["last_name"] ?? "";
$email = $values["email"] ?? "";

?>
  <ul>
    <li><a href="add-recipe.php">Add recipe</a></li>
    <li><a href="login.php">Login</a></li>
    <li><a href="sign-up.php">Sign up</a></li>
  </ul>

  <main>
    <h1>Sign Up</h1>
    <?php 
      if(sizeof($errors)) {
        echo ('
          <div class="errors">
            <h3>Please fix these errors</h3>
            <ul>'
          );
        foreach($errors as $err) {
          echo '<li>' . $err . '</li>';
        }
        echo ('
            </ul>
          </div>
        ');
      }
    ?>
    
    <form action="sign-up.php" method="post" class="user">
      <label for="first_name">First Name</label>
      <input type="text" id="first_name" name="first_name" value="<?= $fname ?>" required><br>
      <label for="last_name">Last Name</label>
      <input type="text" id="last_name" name="last_name" value="<?= $lname ?>" required><br>
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" value="<?= $email ?>" required><br>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required><br>
      <label for="password_confirm">Confirm Password</label>
      <input type="password" id="password_confirm" name="password_confirm" required><br>
      <input type="submit" value="Sign Up">
    </form>
  </main>

<?php
include('partials/footer.php');
?>
