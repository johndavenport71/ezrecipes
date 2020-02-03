<?php

define('ROOT', '/ezrecipes/');
define('CSS_PATH', ROOT . 'css/');
define('ASSETS', ROOT . 'assets/');

/**
* Dump and Die
*
* @param 	 Any 	 $var
* @return 	 void
*/
function dd($var) {
  var_dump($var);
  die();
}