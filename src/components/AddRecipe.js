import React, { useState } from 'react';
import FileDropzone from './form_components/FileDropzone';
import IngredientsInput from './form_components/IngredientsInput';
import axios from 'axios';
import { useHistory } from 'react-router-dom';
import stringToArray from '../utils/stringToArray';

/*

**TO DO: FORM SUBMISSION**

*/
const AddRecipe = (props) => {
  const history = useHistory();
  const api = process.env.REACT_APP_API_PATH;
  const user_id = props.user_id ? props.user_id : 0;

  const [values, setValues] = useState({
    recipe_title: "",
    description: "",
    steps: "",
    fat: 0,
    calories: 0,
    protein: 0,
    sodium: 0,
    categories: "",
    all_ingredients: [],
    recipe_image: ""
  });

  const handleChangeDirectly = (key, value) => {
    setValues({ ...values, [key]: value });
  }

  return (
    <main>
      <h1>Add a New Recipe</h1>
      <form id="add-recipe" onSubmit={handleSubmit} encType="multipart/form-data">
        <div className="half-width">
          <label htmlFor="recipe_title">What do you call your recipe?<sup>*</sup>:</label>
          <input 
            type="text" 
            id="recipe_title" 
            name="recipe_title" 
            required
            value={values.recipe_title}
            onChange={evt => handleChangeDirectly("recipe_title", evt.target.value)}
          />

          <label htmlFor="description">Tell us a little about it<sup>*</sup>:</label>
          <textarea 
            id="description" 
            name="description" 
            maxLength="255" 
            rows="8" 
            cols="50" 
            required
            value={values.description}
            onChange={evt => handleChangeDirectly("description", evt.target.value)}
          ></textarea>
        </div>

        <div className="half-width">
          <FileDropzone values={values} setValues={setValues} />
        </div>

        <fieldset className="full-width nutrition">
          <legend>Nutrition Information (optional)</legend>
          <label htmlFor="calories">Calories
            <input type="text" value={values.calories} onChange={e => handleChangeDirectly("calories", e.target.value)} />
          </label>
          <label htmlFor="fat">Fat
            <input type="text" value={values.fat} onChange={e => handleChangeDirectly("fat", e.target.value)} />
          </label>
          <label htmlFor="protein">Protein
            <input type="text" value={values.protein} onChange={e => handleChangeDirectly("protein", e.target.value)} />
          </label>
          <label htmlFor="sodium">Sodium
            <input type="text" value={values.sodium} onChange={e => handleChangeDirectly("sodium", e.target.value)} />
          </label>
        </fieldset>

        <IngredientsInput values={values} setValues={setValues} />

        <div className="full-width"> 
          <label htmlFor="steps">
            How do you make it?<sup>*</sup>
            <span>Put each step on its own line</span>
          </label>
          <textarea 
            id="steps" 
            name="steps" 
            rows="8" 
            cols="50" 
            required
            value={values.steps}
            onChange={evt => handleChangeDirectly("steps", evt.target.value)}
          ></textarea>
        </div>

        <div className="half-width">
          <label htmlFor="categories">
            Add tags for your recipe:
            <span>Put each tag on its own line</span>
          </label>
          <textarea 
            id="categories" 
            name="categories" 
            rows="8" 
            cols="50" 
            placeholder="Spicy, Healthy, Italian"
            value={values.categories}
            onChange={evt => handleChangeDirectly("categories", evt.target.value)}
          ></textarea>
        </div>
        <input type="submit" value="Submit" />
      </form>
    </main>
  );

  function handleSubmit(event) {
    event.preventDefault();
    let url = api + "recipes.php";
    let newIngredients = values.all_ingredients;
    const ingredient = document.getElementById("ingr_name1");
    if(ingredient.value.length > 0) {
      newIngredients.push({ name: ingredient.value });
    }
    const newSteps = stringToArray(values.steps);
    const newCategories = stringToArray(values.categories);
    setValues({ ...values, all_ingredients: newIngredients, steps: newSteps, categories: newCategories });
    
    let params = new FormData();
    params.append("recipe_title", values.recipe_title);
    params.append("recipe_desc", values.description);
    params.append("fat", values.fat);
    params.append("calories", values.calories);
    params.append("sodium", values.sodium);
    params.append("protein", values.protein);
    params.append("directions", values.steps);
    params.append("user_id", user_id ? user_id : 0);
    params.append("all_ingredients", values.all_ingredients);
    params.append("categories", values.categories);
    params.append("recipe_image", values.recipe_image);
    
    axios.post(url, params)
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

export default AddRecipe;
