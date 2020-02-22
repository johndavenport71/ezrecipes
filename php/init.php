<?php
  ob_start();
  session_start();

  $currentDir = dirname(__FILE__);

  define('ROOT', dirname($currentDir) . "/");
  define('CSS_PATH', ROOT . '/css/');
  define('ASSETS', ROOT . '/assets/');
  define('PARTIALS', ROOT . '/partials/');
  define('DB_PATH', ROOT . '/db/');
  define('UPLOADS', ROOT . '/uploads/');

  include("functions.php");
  include(DB_PATH . "db-connect.php");

  $_SESSION["is_logged_in"] = $_SESSION["is_logged_in"] ?? false;
  $_SESSION["user_name"] = $_SESSION["user_name"] ?? "";
?>