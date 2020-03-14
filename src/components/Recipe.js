import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import Ingredients from './recipe_components/Ingredients';
import RecipeSteps from './recipe_components/RecipeSteps';
import Categories from './recipe_components/Categories';
import LoadingLines from './LoadingLines';

const Recipe = (props) => {
  const { id } = useParams();
  const api = process.env.REACT_APP_API_PATH;
  const root = process.env.REACT_APP_ROOT;
  const [recipe, setRecipe] = useState({});
  const session = JSON.parse(window.sessionStorage.getItem('user'));

  const fetchRecipe = () => {
    const url = api + 'recipes.php?id=' + id;
    fetch(url).then(res => {return res.json();}).then(res => {
      setRecipe(res.recipe);
    });
  }

  useEffect(() => {
    fetchRecipe();
    // eslint-disable-next-line
  },[])

  return (
    <main id="single-recipe">
      {session && session.user_id == recipe.user_id && 
        <>
        <a href={`/edit-recipe/${recipe.id}`}>Edit</a>
        <button>Delete</button>
        </>
      }
      <h1>{recipe.title}</h1>
      {recipe.img_path && <img src={root + recipe.img_path} alt={recipe.title} width="400" height="auto" />}
      <p>{recipe.description && recipe.description}</p>
      {recipe.nutrition ?
        <div className="nutrition">
          {recipe.nutrition.calories && <p>Calories: {recipe.nutrition.calories}</p>}
          {recipe.nutrition.fat && <p>Fat: {recipe.nutrition.fat} grams</p>}
          {recipe.nutrition.protein && <p>Protein: {recipe.nutrition.protein} grams</p>}
          {recipe.nutrition.sodium && <p>Sodium: {recipe.nutrition.sodium} grams</p>}
        </div>
        :
        <LoadingLines />
      }
      <h2>Ingredients</h2>
      {recipe.ingredients ?
        <Ingredients ingredients={recipe.ingredients} />
        :
        <LoadingLines />
      }
      <h2>Directions</h2>
      {recipe.steps ?
        <RecipeSteps steps={recipe.steps} />
        :
        <LoadingLines />
      }
      {recipe.categories && <Categories categories={recipe.categories} />}
    </main>
  );
}

export default Recipe;
