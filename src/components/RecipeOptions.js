import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';
import Alert from './Alert';
import { filter } from 'lodash';

const RecipeOptions = ({ recipe, session }) => {

  const api = process.env.REACT_APP_API_PATH;
  const [errors, setErrors] = useState([]);
  const [message, setMessage] = useState(null);
  const savedRecipes = JSON.parse(window.sessionStorage.getItem('saved_recipes'));
  const isSaved = filter(savedRecipes, {id: recipe.id});

  const saveRecipe = () => {
    const url = api + 'save.php';
    let params = new URLSearchParams();
    params.append('user_id', session.user_id);
    params.append('recipe_id', recipe.id);
    axios.post(url, params)
    .then(res => {
      console.log(res);
      if(res.data.status === 1) {
        setMessage(res.data.status_message);
        window.sessionStorage.setItem('saved_recipes', JSON.stringify(res.data.saved_recipes.recipes));
      } else if(res.data.status === 0) {
        setErrors([res.data.status_message]);
      } else {
        setErrors(['Something went wrong, please try again.'])
      }
    })
    .catch(err => console.log(err));
  }

  const removeRecipe = () => {
    let url = api + 'save.php?';
    let params = new URLSearchParams();
    params.append('user_id', session.user_id);
    params.append('recipe_id', recipe.id);
    url += params.toString();
    axios.delete(url)
    .then(res => {
      console.log(res);
      setMessage(res.data.status_message);
      window.sessionStorage.setItem('saved_recipes', JSON.stringify(res.data.saved_recipes.recipes));
    })
    .catch(err => console.log(err));
  }

  useEffect(()=>{
    console.log('saved: ', savedRecipes);
    console.log('is saved: ', isSaved.length);
  },[savedRecipes, isSaved]);

  return (
    <div className="recipe-options">
      {session && session.user_id == recipe.user_id && 
        <>
        <a href={`/edit-recipe/${recipe.id}`}>Edit</a>
        <button>Delete</button>
        </>
      }
      {isSaved.length > 0 ? 
      <button onClick={removeRecipe}>Remove From Saved</button>
      :
      <button onClick={saveRecipe}>Save Recipe</button>
      }
      {errors.length > 0 && <Alert errors={errors} setOpen={() => setErrors([])} />}
      {message && <Alert message={message} setOpen={() => setMessage(null)} />}
    </div>
  );
}

RecipeOptions.propTypes = {
  recipe: PropTypes.object.isRequired,
  session: PropTypes.object.isRequired
};

export default RecipeOptions;
