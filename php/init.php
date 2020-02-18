<?php
  ob_start();
  session_start();

  include("functions.php");
  include("db/db-connect.php");
  include(".env.php");

  $_SESSION["is_logged_in"] = $_SESSION["is_logged_in"] ?? false;
  $_SESSION["user_name"] = $_SESSION["user_name"] ?? "";
?>