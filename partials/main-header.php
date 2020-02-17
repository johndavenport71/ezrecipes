<header>
  <h1><a href="index.php">EZ <img src="<?= ASSETS ?>/chef.svg" alt="Chef outline icon" class="chef-icon" width="50" height="50"> Recipes</a></h1>
  <div>
    <input type="search" id="search" name="search" placeholder="Search">

    <?php
      if($_SESSION["is_logged_in"]) {
        echo '<span>Hi there, '. ucfirst($_SESSION["user_name"]) ."</span>";
        echo '<a href="logout.php">Logout</a>';
      } else {
        echo ('
          <a href="sign-up.php" class="button">Sign Up!</a>
          <a href="login.php">Login</a>
        ');
      }
    ?>
  </div>
</header>
