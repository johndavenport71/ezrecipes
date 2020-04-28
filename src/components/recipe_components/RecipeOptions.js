import React, { useState } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';
import Alert from '../Global/Alert';
import { filter } from 'lodash';
import Modal from '../Global/Modal';

const RecipeOptions = ({ recipe, session }) => {

  const api = process.env.REACT_APP_API_PATH;
  const [errors, setErrors] = useState([]);
  const [message, setMessage] = useState(null);
  const [open, setOpen] = useState(false);
  const [deleteSuccess, setDeleteSuccess] = useState(false);
  const savedRecipes = JSON.parse(window.sessionStorage.getItem('saved_recipes'));
  const isSaved = filter(savedRecipes, {id: recipe.id});
  const showOptions = session.user_id == recipe.user_id || session.member_level == 'a';

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

  const handleDelete = () => {
    let url = api + 'recipes.php?';
    let params = new URLSearchParams();
    params.append('userID', session.user_id);
    params.append('recipeID', recipe.id);
    url += params.toString();
    axios.delete(url)
    .then(res => {
      console.log(res);
      if(res.data.status === 1) {
        setDeleteSuccess(true);
      } else {
        setErrors([res.data.status_message]);
      }
    })
    .catch(err => console.log(err));
  }

  return (
    <div className="recipe-options">
      {showOptions && 
        <>
        <a href={`/edit-recipe/${recipe.id}`} className="secondary-button">Edit</a>
        <button className="secondary-button warning" onClick={() => setOpen(true)}>Delete</button>
        </>
      }
      {isSaved.length > 0 ? 
      <button onClick={removeRecipe} className="secondary-button">Remove From Saved</button>
      :
      <button onClick={saveRecipe} className="secondary-button">Save Recipe</button>
      }
      {errors.length > 0 && <Alert errors={errors} setOpen={() => setErrors([])} />}
      {message && <Alert message={message} setOpen={() => setMessage(null)} />}
      {open && 
        <Modal>
          {deleteSuccess ?
          <div>
            <h3>Recipe Deleted</h3>
            <a className="secondary-button" href="/">Return Home</a>
          </div>  
          :
          <div className="modal-body">
            <h3>Are you sure you want to delete this recipe?</h3>
            <button className="secondary-button" onClick={()=>setOpen(false)}>Cancel</button>
            <button className="secondary-button warning" onClick={handleDelete}>Delete</button>
          </div>
        }
        </Modal>
      }
    </div>
  );
}

RecipeOptions.propTypes = {
  recipe: PropTypes.object.isRequired,
  session: PropTypes.object.isRequired
};

export default RecipeOptions;
