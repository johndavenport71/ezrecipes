<?php

include('partials/head.php');
include('partials/main-header.php');

?>
  <ul>
    <li><a href="add-recipe.php">Add recipe</a></li>
    <li><a href="login.php">Login</a></li>
    <li><a href="sign-up.php">Sign up</a></li>
  </ul>

  <main>
    <form>
      <label for="first_name">First Name</label>
      <input type="text" id="first_name" name="first_name"><br>
      <label for="last_name">Last Name</label>
      <input type="text" id="last_name" name="last_name"><br>
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email"><br>
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
