<?php

include('utils.php');
include('db/query-snippets.php');
include('recipe.obj.php');

/**
* Get a recipe by ID
*
* @param 	 PDO 	 $conn
* @param 	 int 	 $id
* @return 	 Object
*/
function getFullRecipe(PDO $conn, int $id) {
  $recipe = getRecipe($conn, $id);
  $ingredients = getIngredients($conn, $id);
  $categories = getCategories($conn, $id);
  return new Recipe($recipe, $ingredients, $categories, 0);
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
  $string = "'";
  foreach($array as $arr) {
    $string .= $arr . "','";
  }
  $string = rtrim($string, ",'','");
  $string .= "'";
  return $string;
}

