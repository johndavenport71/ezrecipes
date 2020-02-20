<?php
include('php/init.php');
include('partials/head.php');
include('partials/main-header.php');

$id = $_GET["id"];
$recipe = getFullRecipe($conn, $id);

//dd($recipe);

?>

<main id="single-recipe">
  <h1><?= $recipe->getTitle() ?></h1>
  <div>
    <div class="recipe-header">
      <p><?= $recipe->getDescription() ?></p>
      <image src="<?= $recipe->getImgPath(); ?>" alt="<?= $recipe->getTitle(); ?>">
    </div>
    
    <h3>Ingredients</h3>
    <ul class="two-column">
    <?php 
      foreach($recipe->getIngredients() as $ingr) {
        echo "<li>". $ingr["amount_desc"] . " " . $ingr["ingredient_name"] ."</li>";
      }
    ?>
    </ul>
    <h3>Directions</h3>
    <ol>
      <?php 
        foreach(str_to_array_dbl_slash($recipe->getSteps()) as $step) {
          echo "<li>". ucfirst($step) ."</li>";
        } 
      ?>
    </ol>
    <h3>Tags</h3>
    <ul class="tags">
      <?php
        foreach($recipe->getCategories() as $cat) {
          echo "<li>". ucfirst($cat) ."</li>";
        }
      ?>
    </ul>
  </div>
</main>

<?php
include('partials/footer.php');
?>