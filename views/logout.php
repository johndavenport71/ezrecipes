<?php 
include('../php/init.php');

session_unset();
session_destroy();

include(PARTIALS . '/head.php');
include(PARTIALS . '/main-header.php');

?>

<main>
  <h1>You have succesfully logged out.</h1>
  <a href="<?= WEB_ROOT ?>/index.php">Return to home page</a>
</main>

<?php
include(PARTIALS . '/footer.php');
?>