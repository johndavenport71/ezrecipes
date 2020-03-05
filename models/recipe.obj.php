<?php

class Recipe implements JsonSerializable {
  private $id;
  private $title;
  private $description;
  private $ingredients;
  private $steps;
  private $nutrition;
  private $categories;
  private $date;
  private $user_id;
  private $rating;
  private $img_path;

  /**
  * Constructor
  *
  * @param 	  Array  $recipe
  * @param    Array  $ingredients
  * @param    Array  $categories
  * @param    int    $rating
  * @return   void
  */
  function __construct(Array $recipe, Array $ingredients, Array $categories, int $rating) {
    $this->id = (int)$recipe["recipe_id"];
    $this->title = $recipe["recipe_title"];
    $this->description = $recipe["recipe_desc"];
    $this->nutrition = array(
      'fat' => $recipe["fat"],
      'calories' => $recipe["calories"],
      'protein' => $recipe["protein"],
      'sodium' => $recipe["sodium"],
    );
    $this->steps = explode("//", $recipe["directions"]);
    $this->date = $recipe["date_added"];
    $this->user_id = (int)$recipe["user_id"];
    $this->img_path = $recipe["recipe_image"];
    $this->categories = $categories;
    $this->ingredients = $ingredients;
    $this->rating = (int)$rating;
  }

  /**
  * Serialize and return the object in JSON
  *
  * @param 	 void 	 
  * @return 	 JSON
  */
  public function jsonSerialize() {
    return get_object_vars($this);
  }

  /*vvvvv Getters and Setters vvvvv*/

  /**
  * Get recipe id
  *
  * @param 	 void 	 
  * @return 	 int
  */
  function getID() {
    return $this->id;
  }

  /**
  * Set recipe id
  *
  * @param 	 int 	 $id
  * @return 	 void
  */
  function setID(int $id) {
    $this->id = $id;
  }

  /**
  * Get recipe title
  *
  * @param 	 void 	 
  * @return 	 String
  */
  function getTitle() {
    return $this->title;
  }

  /**
  * Set recipe title
  *
  * @param 	 String 	 $title
  * @return 	 void
  */
  function setTitle(String $title) {
    $this->title = $title;
  }

  /**
  * Get recipe description
  *
  * @param 	 void 	 
  * @return 	 String
  */
  function getDescription() {
    return $this->description;
  }

  /**
  * Set recipe description
  *
  * @param 	 String 	 $description
  * @return 	 void
  */
  function setDescription(String $description) {
    $this->description = $description;
  }

  /**
  * Get recipe steps
  *
  * @param 	 void 	 
  * @return 	 String
  */
  function getSteps() {
    return $this->steps;
  }

  /**
  * Set recipe steps
  *
  * @param 	 String 	 $steps
  * @return 	 void
  */
  function setSteps(String $steps) {
    $this->steps = $steps;
  }

  /**
  * Get user id for recipe
  *
  * @param 	 void 	 
  * @return 	 int
  */
  function getUserID() {
    return $this->user_id;
  }

  /**
  * Set user id for recipe
  *
  * @param 	 int 	 $id
  * @return 	 void
  */
  function setUserID(int $id) {
    $this->user_id = $id;
  }

  /**
  * Get recipe prep time
  *
  * @param 	 void 	 
  * @return 	 int
  */
  function getPrepTime() {
    return $this->prep_time;
  }

  /**
  * Set recipe prep time
  *
  * @param 	 int 	 $time
  * @return 	 void
  */
  function setPrepTime(int $time) {
    $this->prep_time = $time;
  }

  /**
  * Get recipe cook time
  *
  * @param 	 void 	 
  * @return 	 int
  */
  function getCookTime() {
    return $this->cook_time;
  }

  /**
  * Set recipe cook time
  *
  * @param 	 int 	 $time
  * @return 	 void
  */
  function setCookTime(int $time) {
    $this->cook_time = $time;
  }

  /**
  * Get recipe image path
  *
  * @param 	 void 	 
  * @return 	 String
  */
  function getImgPath() {
    return $this->img_path;
  }

  /**
  * Set recipe image path
  *
  * @param 	 String 	 $path
  * @return 	 void
  */
  function setImgPath(String $path) {
    $this->img_path = $path;
  }

  /**
  * Get recipe's categories
  *
  * @param 	 void 	 
  * @return 	 Array
  */
  function getCategories() {
    return $this->categories;
  }

  /**
  * Set recipe's categories
  *
  * @param 	 Array 	 $categories
  * @return 	 void
  */
  function setCategories(Array $categories) {
    $this->categories = $categories;
  }

  /**
  * Get recipe's ingredients
  *
  * @param 	 void 	 
  * @return 	 Array
  */
  function getIngredients() {
    return $this->ingredients;
  }

  /**
  * Set recipe's ingredients
  *
  * @param 	 Array 	 $ingredients
  * @return 	 void
  */
  function setIngredients(Array $ingredients) {
    $this->ingredients = $ingredients;
  }

  /**
  * Get recipe rating
  *
  * @param 	 void 	 
  * @return 	 int
  */
  function getRating() {
    return $this->rating;
  }

  /**
  * Set recipe rating
  *
  * @param 	 int 	 $rating
  * @return 	 void
  */
  function setRating(int $rating) {
    $this->rating = $rating;
  }

}


?>