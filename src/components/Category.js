import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import LoadingCard from './LoadingCard';
import RecipeCard from './RecipeCard';

const Category = () => {
  const { categories } = useParams();
  const api = process.env.REACT_APP_API_PATH;
  const [recipes, setRecipes] = useState([]);

  useEffect(()=>{
    const fetchRecipes = (api, cats) => {
      let url = api + 'categories.php?';
      let params = new URLSearchParams();
      params.append('categories', encodeURI(cats));
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
      <h1>{categories} Recipes</h1>
      {recipes.length === 0 ? 
        <LoadingCard /> 
        : 
        <section className="recipes-grid">
          {recipes.map((recipe, i) => <RecipeCard recipe={recipe} key={i} />)}
        </section>
      }
    </main>
  );
}

export default Category;