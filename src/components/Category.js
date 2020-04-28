import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import LoadingCard from './Global/LoadingCard';
import RecipeCard from './Global/RecipeCard';

const Category = () => {
  const { categories } = useParams();
  const api = process.env.REACT_APP_API_PATH;
  const [recipes, setRecipes] = useState([]);

  useEffect(()=>{
    const fetchRecipes = (api, cats) => {
      let url = api + 'categories.php?';
      let params = new URLSearchParams();
      if(cats.includes('&')) {
        params.append('categories', encodeURIComponent(cats));
      } else {
        params.append('categories', decodeURIComponent(cats));
      }
      params.append('limit', 100);
      url += params.toString();
      axios.get(url)
      .then(res => {
        if(res.data.status === 1) {
          setRecipes(res.data.data)
        } else {
          console.log(res.data.status_message);
        }
      })
      .catch(err => console.log(err));
    }
    fetchRecipes(api, categories);
    console.log(categories);
  },[api, categories]);

  return (
    <main>
      <h2>{decodeURIComponent(categories)} Recipes</h2>
      {recipes.length === 0 ? 
        <LoadingCard /> 
        : 
        <section className="recipes-grid">
          {recipes.map((recipe, i) => <RecipeCard category={`/recipes/${categories}`} recipe={recipe} key={i} />)}
        </section>
      }
    </main>
  );
}

export default Category;
