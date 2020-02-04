<?php

include('partials/head.php');
include('partials/main-header.php');

$id = 9;

$sql = "SELECT * FROM recipes WHERE recipe_id = $id LIMIT 1";

$recipe = $conn->query($sql)->fetch();

$steps = json_decode($recipe['steps']);
$ingredients = json_decode($recipe['ingredients']);

//dd($recipe);

?>

<main>
  <h2><?= $recipe['title'] ?></h2>
  <p><?= $recipe['recipe_desc'] ?></p>
  <ul>
  <?php 
    foreach($ingredients as $name) {
      echo "<li>$name</li>";
    }
  ?>
  </ul>
  <ul>
    <?php
      foreach($steps as $i => $step) {
        echo "<li>Step ". ($i + 1) .": " . ucfirst($step) . "</li>";
      }
    ?>
  </ul>
</main>

<?php
include('partials/footer.php');
?>