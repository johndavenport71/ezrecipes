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

  if(sizeof($errors)) {
    dd($errors);
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
    <form action="sign-up.php" method="post">
      <label for="first_name">First Name</label>
      <input type="text" id="first_name" name="first_name" value="<?= $fname ?>"><br>
      <label for="last_name">Last Name</label>
      <input type="text" id="last_name" name="last_name" value="<?= $lname ?>"><br>
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" value="<?= $email ?>"><br>
      <label for="password">Password</label>
      <input type="password" id="password" name="password"><br>
      <label for="password_confirm">Confirm Password</label>
      <input type="password" id="password_confirm" name="password_confirm"><br>
      <input type="submit" value="Sign Up">
    </form>
  </main>

<?php
include('partials/footer.php');
?>
