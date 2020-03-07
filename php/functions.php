<?php

include('utils.php');
include(DB_PATH . '/query-snippets.php');

/**
* Parse steps into a delimited string
*
* @param 	 Array 	 $array
* @return 	 String
*/
function parseSteps(Array $array) {
  return implode("//", $array);
}

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

/**
* Split a string on newline character and replace with //
*
* @param 	 String 	 $str
* @return 	 String
*/
function nl_dbl_slash(String $str) {
  if(!strlen($str)){
    return;
  }
  $str = rtrim(strtolower($str));
  $newStr = str_replace("\n", "//", $str);
  return $newStr;
}

/**
* Parse a string to array based on "//"
*
* @param 	 String 	 $str
* @return 	 Array
*/
function str_to_array_dbl_slash(String $str) {
  if(!strlen($str)) {
    return [];
  }
  $str = rtrim(strtolower($str));
  $array = preg_split("/\/\//", $str);
  return $array;
}

/**
* Create an associative array of ingredients and amounts
*
* @param 	 String 	 $str
* @return 	 Array
*/
function parseIngredients(String $str) {
  $array = str_to_array_dbl_slash($str);

  foreach($array as $arr) {
    $key = substr($arr, 0, strpos($arr, "||"));
    $value = substr($arr, strpos($arr, "||"));
    $value = ltrim($value, "||");
    $newArr[$key] = $value;
  }

  return $newArr;
}

/**
* Implode an array putting each element in quotes and removing the last comma
*
* @param 	 Array 	 $array
* @return 	 String
*/
function preciseImplode(Array $array) {
  $reg = "/(([a-z]|[A-Z])\'([a-z]|[A-Z]))|([a-z]\'(?!\|\|))/";
  $string = "'";
  $params = implode("'||'", $array);
  $params = preg_replace($reg, "''", $params);
  $params = str_replace("||", ",", $params);
  $params = str_replace(";", "\;", $params);
  $string .= $params;
  $string = rtrim($string, ",");
  $string .= "'";
  return $string;
}

/**
 * Replace commas with pipes
 * 
 * @param String $string
 * @return String
 */
function queryStringRegexp(String $string) {
  return str_replace(",", "|", $string);
}

/**
 * Convert an array to a string delimited with pipes
 * 
 * @param   Array   $array
 * @return  String
 */
function regexpImplode(Array $array) {
  return implode("|", $array);
}

/**
* Generate a random token
*
* @param 	 void 	 
* @return 	 String
*/
function generateToken() {
  $token = bin2hex(random_bytes(3));
  $token .= '-' . bin2hex(random_bytes(3));
  $token .= '-' . bin2hex(random_bytes(3));
  $token .= '-' . bin2hex(random_bytes(3));

  return $token;
}

/**
* Check user input against a list of approved items
*
* @param 	 String 	 $str
* @return 	 boolean
*/
function userVerify(String $str) {
  $answers = ["recipes", "recipe", "food", "meals", "cooking"];
  return in_array(strtolower($str), $answers);
}

