import React, { useState, useEffect } from 'react';
import axios from 'axios';
import RecipeCard from './RecipeCard';

const SavedRecipes = ({ id }) => {

  const api = process.env.REACT_APP_API_PATH;
  const [recipes, setRecipes] = useState([]);

  const fetchRecipes = (id, api) => {
    const url = api + 'save.php?user_id=' + id;
    axios.get(url)
    .then(res => {
      console.log(res);
      setRecipes(res.data.recipes);
    })
    .catch(err => console.log(err));
  }

  useEffect(()=>{
    fetchRecipes(id, api);
  },[]);

  return (
    <div>
      <h2>Saved Recipes</h2>
      <section className="recipes-grid">
        {recipes.length > 0 && 
          recipes.map(recipe => (<RecipeCard key={recipe.id} recipe={recipe} />))
        }
      </section>
    </div>
  );
}

export default SavedRecipes;
