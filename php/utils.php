<?php

define('ROOT', '/ezrecipes/');
define('CSS_PATH', ROOT . 'css/');
define('ASSETS', ROOT . 'assets/');
define('PARTIALS', ROOT . 'partials/');
define('DB_PATH', ROOT . 'db/');

/**
* Dump and Die
*
* @param 	 Any 	 $var
* @return 	 void
*/
function dd($var) {
  echo '<pre>';
  var_dump($var);
  echo '</pre>';
  die();
}

/**
* html special characters shortcut
*
* @param 	 String 	 $str
* @return 	 String
*/
function h(String $str) {
  return htmlspecialchars($str);
}