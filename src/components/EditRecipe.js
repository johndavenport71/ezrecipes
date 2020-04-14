import React, { useEffect, useState } from 'react';
import { useParams, useHistory } from 'react-router-dom';
import axios from 'axios';
import arrayToString from '../utils/arrayToString';
import RecipeForm from './form_components/RecipeForm';

const EditRecipe = () => {
  const { id } = useParams();
  const history = useHistory();
  const api = process.env.REACT_APP_API_PATH;
  const [recipe, setRecipe] = useState({});
  const session = JSON.parse(window.sessionStorage.getItem('user'));

  useEffect(()=>{
    const fetchRecipe = (api, id, setRecipe) => {
      const url = api + 'recipes.php?id=' + id;
      axios.get(url)
      .then(res => {
        const formatted = res.data.recipe;
        formatted.steps = arrayToString(formatted.steps, "\n");
        formatted.categories = arrayToString(formatted.categories, "\n");
        formatted.fat = formatted.nutrition.fat;
        formatted.calories = formatted.nutrition.calories;
        formatted.protein = formatted.nutrition.protein;
        formatted.sodium = formatted.nutrition.sodium;
        setRecipe(formatted);
      })
      .catch(err => console.log(err));
    }
    fetchRecipe(api, id, setRecipe);
  },[api, id]);

  const handleChangeDirectly = (key, value) => {
    setRecipe({...recipe, [key]: value});
  }
  
  return (
    <main>
      <h1>Edit {recipe && recipe.recipe_title}</h1>
      {recipe && 
        <RecipeForm values={recipe} setValues={setRecipe} handleChangeDirectly={handleChangeDirectly} handleSubmit={handleSubmit} />
      }
    </main>
  );

  function handleSubmit(event) {
    event.preventDefault();
    let url = api + "recipes.php";
    let newIngredients = recipe.ingredients;
    const ingredient = document.getElementById("ingr_name1");
    if(ingredient.value.length > 0) {
      newIngredients.push(ingredient.value);
    }

    const files = document.getElementById('image');
    let file;
    if(files.files) {
      file = files.files[0];
    }

    const ingredients = arrayToString(newIngredients, '//');
    
    let params = new FormData();
    params.append("recipe_id", recipe.id);
    params.append("recipe_title", recipe.title);
    params.append("recipe_desc", recipe.description);
    params.append("fat", recipe.fat);
    params.append("calories", recipe.calories);
    params.append("sodium", recipe.sodium);
    params.append("protein", recipe.protein);
    params.append("directions", recipe.steps);
    params.append("user_id", session ? session.user_id : 0);
    params.append("user_auth", session.uuid);
    params.append("all_ingredients", ingredients);
    params.append("categories", recipe.categories);
    params.append("image", file);

    axios.put(url, params)
    .then(res => {
      if(res.data.status === 1) {
        history.push(`/recipe/${res.data.recipe_id}`);
      } else {
        console.log("TO DO: error handling",res);
      }
    })
    .catch(err => console.log(err));

  }
}

export default EditRecipe;
