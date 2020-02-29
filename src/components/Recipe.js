import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import Ingredients from './recipe_components/Ingredients';
import RecipeSteps from './recipe_components/RecipeSteps';
import upperCaseFirst from '../utils/upperCase';

const Recipe = () => {
  const { id } = useParams();
  const api = process.env.REACT_APP_API_PATH;
  const [recipe, setRecipe] = useState({});

  const fetchRecipe = () => {
    const url = api + 'recipes.php?id=' + id;
    fetch(url).then(res => {return res.json();}).then(res => {
      console.log(res);
      setRecipe(res.recipe);
    });
  }

  useEffect(() => {
    fetchRecipe();
  },[])

  return (
    <main>
      <h1>{recipe.title}</h1>
      {recipe.categories && recipe.categories.map((cat, i) => <span className="category" key={i}>{upperCaseFirst(cat)}</span>)}
      <p>{recipe.description && recipe.description}</p>
      <p>{recipe.prep_time && recipe.prep_time} min</p>
      <p>{recipe.cook_time && recipe.cook_time} min</p>
      <h2>Ingredients</h2>
      {recipe.ingredients && 
        <Ingredients ingredients={recipe.ingredients} />
      }
      <h2>Directions</h2>
      {recipe.steps && 
        <RecipeSteps steps={recipe.steps} />
      }
      
    </main>
  );
}

export default Recipe;
