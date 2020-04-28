import React, { useState } from 'react';
import axios from 'axios';
import { useHistory } from 'react-router-dom';
import arrayToString from '../utils/arrayToString';
import RecipeForm from './form_components/RecipeForm';
import Alert from './Global/Alert';

const AddRecipe = (props) => {
  const history = useHistory();
  const api = process.env.REACT_APP_API_PATH;
  const session = JSON.parse(window.sessionStorage.getItem('user'));
  const [showForm, setShowForm] = useState(Boolean(session));
  const [errors, setErrors] = useState([]);

  const [values, setValues] = useState({
    title: "",
    description: "",
    steps: "",
    fat: 0,
    calories: 0,
    protein: 0,
    sodium: 0,
    categories: "",
    ingredients: [],
    image: ""
  });

  const handleChangeDirectly = (key, value) => {
    setValues({ ...values, [key]: value });
  }

  return (
    <main>
      <h1>Add a New Recipe</h1>
      {!showForm ? 
      <div className="center-content">
        <p>Sign in or register for an account to add your recipe!</p>
        <p>Or</p>
        <button className="button-link" onClick={() => setShowForm(true)}>Add a recipe anonymously</button>
      </div>
      :
      <RecipeForm values={values} setValues={setValues} handleChangeDirectly={handleChangeDirectly} handleSubmit={handleSubmit} />
      }
      {errors.length > 0 && 
        <Alert errors={errors} setOpen={() => setErrors([])} />
      }
    </main>
  );

  function handleSubmit(event) {
    event.preventDefault();
    let url = api + "recipes.php";
    let newIngredients = values.ingredients;
    const ingredient = document.getElementById("ingr_name1");
    if(ingredient.value.length > 0) {
      newIngredients.push(ingredient.value);
    }

    const files = document.getElementById('image');
    let file;
    if(files.files) {
      file = files.files[0];
    }

    console.log(file);

    const ingredients = arrayToString(newIngredients, '//');
    
    let params = new FormData();
    params.append("recipe_title", values.title);
    params.append("recipe_desc", values.description);
    params.append("fat", values.fat);
    params.append("calories", values.calories);
    params.append("sodium", values.sodium);
    params.append("protein", values.protein);
    params.append("directions", values.steps);
    params.append("user_id", session ? session.user_id : 0);
    params.append("all_ingredients", ingredients);
    params.append("categories", values.categories);
    params.append("image", file);

    axios.post(url, params, {headers: {'Content-Type': 'multipart/form-data'}})
    .then(res => {
      console.log(res);
      if(res.data.status === 1) {
        history.push(`/recipe/${res.data.recipe_id}`);
      } else {
        setErrors(["Failed to add recipe. Please check the information you entered and try again."]);
      }
    })
    .catch(err => console.log(err));

  }

}

export default AddRecipe;
