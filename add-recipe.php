<?php

include('partials/head.php');
include('partials/main-header.php');
?>

<main>
  <h2>Add your recipe to the mix!</h2>
  <form id="add-recipe" action="form-handler.php" method="post">
    <label for="recipe_title">What do you call your recipe?<sup>*</sup>:</label>
    <input type="text" id="recipe_title" name="recipe_title" required><br>

    <label for="description">Tell us a little about it<sup>*</sup>:</label><br>
    <textarea id="description" name="description" maxlength="255" rows="5" cols="50" required></textarea><br>

    <label for="prep_time">Prep time<sup>*</sup>:</label>
    <input type="text" id="prep_time" name="prep_time" required><br>

    <label for="cook_time">Cook time<sup>*</sup>:</label>
    <input type="text" id="cook_time" name="cook_time" required><br>

    <label for="steps">How do you make it?<sup>*</sup></label><br>
    <textarea id="steps" name="steps" placeholder="Put each step on its own line" rows="5" cols="50" required></textarea><br>

    <div id="form-ingredients">
      
      <div id="ingredient-inputs">
        <label for="ingr_name1">Ingredient</label>
        <label for="ingr_amt1">Amount</label>
        <input name="ingr_name1" id="ingr_name1" class="name-input">
        <input name="ingr_amt1" id="ingr_amt1" >
      </div>
      
    </div>
    
    <input type="hidden" id="all-ingredients" name="all_ingredients">
    <label for="categories">Add tags for your recipe: (Spicy, Mexican)</label><br>
    <textarea id="categories" name="categories" rows="5" cols="50" placeholder="Put each tag on its own line"></textarea><br>
    <input type="submit" value="Submit">
  </form>
</main>

<?php
include('partials/footer.php');
?>
