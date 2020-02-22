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

/**
* Delete a directory and all files in it
*
* @param 	 String 	 $dir
* @return 	 Boolean
*/
function delTree($dir) { 
  if(empty($dir)) {
    return false;
  }

  $files = array_diff(scandir($dir), array('.', '..')); 

  foreach ($files as $file) { 
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
  }

  return rmdir($dir); 
} 