<header>
  <h1><a href="<?= WEB_ROOT ?>/index.php">EZ <img src="<?= ASSETS ?>/chef.svg" alt="Chef outline icon" class="chef-icon" width="50" height="50"> Recipes</a></h1>
  <div>
    <input type="search" id="search" name="search" placeholder="Search">

    <?php
      if(isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"]) {
        echo '<span>Hi there, '. ucfirst($_SESSION["user_name"]) .' <image src="'.$_SESSION["user_img"].'" alt="Picture of '.$_SESSION["user_name"].'" class="user-image"> </span>';
        echo '<a href="logout.php">Logout</a>';
      } else {
        echo ('
          <a href="'. WEB_ROOT .'/views/sign-up.php" class="button">Sign Up!</a>
          <a href="'. WEB_ROOT .'/views/login.php">Login</a>
        ');
      }
    ?>
  </div>
</header>
