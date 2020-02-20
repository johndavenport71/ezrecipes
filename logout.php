<?php 
include('php/init.php');

session_unset();
session_destroy();

include('partials/head.php');
include('partials/main-header.php');

?>

<main>
  <h1>You have succesfully logged out.</h1>
  <a href="index.php">Return to home page</a>
</main>

<?php
include('partials/footer.php');
?>