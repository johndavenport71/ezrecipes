<?php

include('partials/head.php');
include('partials/main-header.php');
include('db/query-snippets.php');

$id = 9;

$sql = "SELECT * FROM recipes WHERE recipe_id = $id LIMIT 1";

$recipe = $conn->query($sql)->fetch();

$steps = json_decode($recipe['steps']);
$ingredients = json_decode($recipe['ingredients']);

$categories = getRecipeCategories($conn, $recipe['recipe_id']);

dd($_SERVER['HTTP_HOST']);

?>

<main>
  <h2><?= $recipe['title'] ?></h2>
  <p><?= $recipe['recipe_desc'] ?></p>
  <h3>Ingredients</h3>
  <ul>
  <?php 
    foreach($ingredients as $name) {
      echo "<li>$name</li>";
    }
  ?>
  </ul>
  <h3>Directions</h3>
  <ul>
    <?php
      foreach($steps as $i => $step) {
        echo "<li>Step ". ($i + 1) .": " . ucfirst($step) . "</li>";
      }
    ?>
  </ul>
  <h3>Tags</h3>
  <ul>
    <?php
      foreach($categories as $cat) {
        echo "<li>". ucfirst($cat['category_name']) ."</li>";
      }
    ?>
  </ul>
</main>

<?php
include('partials/footer.php');
?>