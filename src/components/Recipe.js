import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import Ingredients from './recipe_components/Ingredients';

const Recipe = () => {
  const { id } = useParams();
  const api = process.env.REACT_APP_API_PATH;
  const [recipe, setRecipe] = useState({});

  useEffect(()=>{
    fetchRecipe();
  },[]);

  const fetchRecipe = () => {
    const url = api + 'recipes.php?id=' + id;
    fetch(url).then(res => {return res.json();}).then(res => {
      console.log(res);
      setRecipe(res.recipe);
    });
  }

  return (
    <main>
      <h1>{recipe.title}</h1>
      <h2>Ingredients</h2>
      {recipe.ingredients && 
        <Ingredients ingredients={recipe.ingredients} />
      }
      
      
    </main>
  );
}

export default Recipe;
