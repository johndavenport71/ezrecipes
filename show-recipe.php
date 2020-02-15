<?php

include('partials/head.php');
include('partials/main-header.php');

$id = $_GET["id"];
$recipe = getFullRecipe($conn, $id);

//dd($recipe);

?>

<main>
  <h2><?= $recipe->getTitle() ?></h2>
  <p><?= $recipe->getDescription() ?></p>
  <h3>Ingredients</h3>
  <ul>
  <?php 
    foreach($recipe->getIngredients() as $ingr) {
      echo "<li>". $ingr["amount_desc"] . " " . $ingr["ingredient_name"] ."</li>";
    }
  ?>
  </ul>
  <h3>Directions</h3>
  <ul>
    <?php 
      foreach(str_to_array_dbl_slash($recipe->getSteps()) as $step) {
        echo "<li>". ucfirst($step) ."</li>";
      } 
    ?>
  </ul>
  <h3>Tags</h3>
  <ul>
    <?php
      foreach($recipe->getCategories() as $cat) {
        echo "<li>". ucfirst($cat) ."</li>";
      }
    ?>
  </ul>
</main>

<?php
include('partials/footer.php');
?>