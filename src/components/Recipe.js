import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import Ingredients from './recipe_components/Ingredients';
import RecipeSteps from './recipe_components/RecipeSteps';
import Categories from './recipe_components/Categories';
import LoadingLines from './LoadingLines';
import LoadingSummary from './recipe_components/LoadingSummary';
import RecipeOptions from './RecipeOptions';

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
      {session && recipe && 
        <RecipeOptions recipe={recipe} session={session} />
      }
      <h1>{recipe.title}</h1>
      <div className="summary">
        {Object.keys(recipe).length > 0 ? 
        <>
        <div>
          <p>{recipe.description && recipe.description}</p>
          {recipe.nutrition && Object.keys(recipe.nutrition).length > 0 &&
            <div className="nutrition">
              {recipe.nutrition.calories && <p>Calories: <span>{recipe.nutrition.calories}</span></p>}
              {recipe.nutrition.fat && <p>Fat: <span>{recipe.nutrition.fat} grams</span></p>}
              {recipe.nutrition.protein && <p>Protein: <span>{recipe.nutrition.protein} grams</span></p>}
              {recipe.nutrition.sodium && <p>Sodium: <span>{recipe.nutrition.sodium} grams</span></p>}
            </div>
          }
        </div>
        {recipe.img_path && <img src={root + recipe.img_path} alt={recipe.title} width="400" height="auto" />}
        </>
        :
        <LoadingSummary />
        }
      </div>
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
