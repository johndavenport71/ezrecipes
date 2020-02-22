<?php
  ob_start();
  session_start();

  define('SITE_ROOT', dirname(__DIR__));

  define('PARTIALS', SITE_ROOT . '/partials');
  define('DB_PATH', SITE_ROOT . '/db');
  define('MODELS', SITE_ROOT . '/models');
  define('VIEWS', SITE_ROOT . '/views');
  define('API_PATH', SITE_ROOT . '/api');

  define('WEB_ROOT', '/ezrecipes');
  
  define('CSS_PATH', WEB_ROOT . '/css');
  define('ASSETS', WEB_ROOT . '/assets'); 
  define('UPLOADS', WEB_ROOT . '/uploads'); 
  define('SCRIPTS', WEB_ROOT . '/js');

  include("functions.php");
  include(DB_PATH . "/db-connect.php");

  $_SESSION["is_logged_in"] = $_SESSION["is_logged_in"] ?? false;
  $_SESSION["user_name"] = $_SESSION["user_name"] ?? "";
?>