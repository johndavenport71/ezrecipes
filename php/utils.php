<?php

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