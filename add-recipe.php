<?php

include('partials/head.php');
include('partials/main-header.php');
?>

<main>
  <h2>Add your recipe to the mix!</h2>
  <form id="add-recipe" action="test/form-handler.php" method="post">
    <label for="recipe_title">What do you call your recipe?<sup>*</sup>:</label>
    <input type="text" id="recipe_title" name="recipe_title" required><br>

    <label for="description">Tell us a little about it<sup>*</sup>:</label><br>
    <textarea id="description" name="description" maxlength="255" rows="5" cols="50" required></textarea><br>

    <label for="total_time">How long does it take? (30 min?, 2 hours?)<sup>*</sup></label>
    <input type="text" id="total_time" name="total_time" required><br>

    <label for="steps">How do you make it?<sup>*</sup></label><br>
    <textarea id="steps" name="steps" placeholder="Put each step on its own line" rows="5" cols="50" required></textarea><br>

    <label for="ingredients">What goes in it?<sup>*</sup></label><br>
    <textarea id="ingredients" name="ingredients" placeholder="Put each ingredient on its own line" rows="5" cols="50" required></textarea><br>

    <label for="categories">Add tags for your recipe: (Spicy, Mexican)</label><br>
    <textarea id="categories" name="categories" rows="5" cols="50" placeholder="Put each tag on its own line"></textarea><br>
    <input type="submit" value="Submit">
  </form>
</main>

<?php
include('partials/footer.php');
?>
