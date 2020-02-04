<?php

include('utils.php');

/**
* Parse a string to an array based on newline character
*
* @param 	 String 	 $str
* @return 	 Array
*/
function prepArray(String $str) {
  if(!strlen($str)){
    return [];
  }
  $str = rtrim(strtolower($str));
  $array = preg_split("/\r\n|\n|\r/", $str);
  return $array;
}

