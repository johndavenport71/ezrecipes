<?php

include('partials/head.php');
include('partials/main-header.php');

$id = $_GET["id"];
$recipe = getFullRecipe($conn, $id);

?>

<main>
  <h2><?= $recipe->getTitle() ?></h2>
  <p><?= $recipe->getDescription() ?></p>
  <h3>Ingredients</h3>
  <ul>
  <?php 
    foreach($recipe->getIngredients() as $ingr) {
      echo "<li>". $ingr["ingredient_amount"] . " " . $ingr["ingredient_name"] ."</li>";
    }
  ?>
  </ul>
  <h3>Directions</h3>
  <p>To Do: split steps into array</p>
  <p><?= $recipe->getSteps(); ?></p>
  <h3>Tags</h3>
  <ul>
    <?php
      foreach($recipe->getCategories() as $cat) {
        echo "<li>". ucfirst($cat['category_name']) ."</li>";
      }
    ?>
  </ul>
</main>

<?php
include('partials/footer.php');
?>