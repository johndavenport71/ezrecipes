import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import Ingredients from './recipe_components/Ingredients';
import RecipeSteps from './recipe_components/RecipeSteps';
import Categories from './recipe_components/Categories';
import LoadingLines from './Global/LoadingLines';
import LoadingSummary from './recipe_components/LoadingSummary';
import RecipeOptions from './recipe_components/RecipeOptions';
import Breadcrumb from './Global/Breadcrumb';
import RecipeRating from './recipe_components/RecipeRating';
import Comments from './recipe_components/Comments';
import axios from 'axios';

const Recipe = (props) => {
  const { id, category } = useParams();
  const api = process.env.REACT_APP_API_PATH;
  const [recipe, setRecipe] = useState({});
  const session = JSON.parse(window.sessionStorage.getItem('user'));

  const fetchRecipe = () => {
    const url = api + 'recipes.php?id=' + id;
    axios.get(url)
    .then(res => {
      if(res.data.status === 1) {
        setRecipe(res.data.recipe);
      } else {
        window.location = "/";
      }
    });
  }

  useEffect(() => {
    fetchRecipe();
    // eslint-disable-next-line
  },[])

  return (
    <main id="single-recipe">
      {category && 
        <Breadcrumb category={category} />
      }
      {session && recipe && 
        <RecipeOptions recipe={recipe} session={session} />
      }
      <h1>{recipe.title}</h1>
      <div className="summary two-column">
        {Object.keys(recipe).length > 0 ?
        <>
          <p>{recipe.description && recipe.description}</p>
          {recipe.nutrition && Object.keys(recipe.nutrition).length > 0 &&
            <div className="nutrition-display">
              {recipe.nutrition.calories && <p>Calories: <span>{recipe.nutrition.calories}</span></p>}
              {recipe.nutrition.fat && <p>Fat: <span>{recipe.nutrition.fat} grams</span></p>}
              {recipe.nutrition.protein && <p>Protein: <span>{recipe.nutrition.protein} grams</span></p>}
              {recipe.nutrition.sodium && <p>Sodium: <span>{recipe.nutrition.sodium} grams</span></p>}
            </div>
          }
        </>
        :
        <LoadingSummary />
        }
      </div>
      {recipe &&
        <RecipeRating recipe={recipe} />
      }
      <div className="two-column">
        <div>
          <h2>Ingredients</h2>
          {recipe.ingredients ?
            <Ingredients ingredients={recipe.ingredients} />
            :
            <LoadingLines />
          }
        </div>
        <div>
          {recipe.img_path && <img src={recipe.img_path.includes('http') ? recipe.img_path : `https://${recipe.img_path}`} alt={recipe.title} width="400" height="auto" />}
        </div>
      </div>
      <h2>Directions</h2>
      {recipe.steps ?
        <RecipeSteps steps={recipe.steps} />
        :
        <LoadingLines />
      }
      {recipe.categories && <Categories categories={recipe.categories} />}
      <Comments id={id} session={session} />
    </main>
  );
}

export default Recipe;
