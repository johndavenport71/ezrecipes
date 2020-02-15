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
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" required placeholder="Email Address"><br>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required placeholder="Password">
      <input type="submit" value="Login">
    </form>
  </main>
 
<?php
include('partials/footer.php');
?>
