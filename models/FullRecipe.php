<?php

require_once('Category.php');
require_once('Ingredient.php');
require_once('Recipe.php');

class FullRecipe implements JsonSerializable {
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
  * @param    PDO    $conn
  * @param    int    $id
  * @param    int    $rating
  * @return   void
  */
  function __construct(PDO $conn, int $id, int $rating) {
    $r = new Recipe($conn);
    $i = new Ingredient($conn);
    $c = new Category($conn);

    $recipe = $r->getRecipeData($id);
    $categories = $c->getCategoriesByRecipe($id);
    $ingredients = $i->getIngredientsByRecipe($id);

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

}

?>
