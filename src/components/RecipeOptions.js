import React, { useState } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';
import Alert from './Alert';

const RecipeOptions = ({ recipe, session }) => {

  const api = process.env.REACT_APP_API_PATH;
  const [errors, setErrors] = useState([]);
  const [message, setMessage] = useState(null);

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
      } else if(res.data.status === 0) {
        setErrors([res.data.status_message]);
      } else {
        setErrors(['Something went wrong, please try again.'])
      }
    })
    .catch(err => console.log(err));
  }

  return (
    <div className="recipe-options">
      {session && session.user_id == recipe.user_id && 
        <>
        <a href={`/edit-recipe/${recipe.id}`}>Edit</a>
        <button>Delete</button>
        </>
      }
      <button onClick={saveRecipe}>Save Recipe</button>
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
