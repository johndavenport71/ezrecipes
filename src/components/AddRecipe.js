import React, { useState } from 'react';
import FileDropzone from './form_components/FileDropzone';
import IngredientsInput from './form_components/IngredientsInput';
import axios from 'axios';
import { useHistory } from 'react-router-dom';

/*

**TO DO: FORM SUBMISSION**

*/
const AddRecipe = () => {
  const history = useHistory();
  const api = process.env.REACT_APP_API_PATH;

  const [values, setValues] = useState({
    recipe_title: "",
    description: "",
    steps: "",
    prep_time: 0,
    cook_time: 0,
    categories: "",
    all_ingredients: [],
    recipe_image: ""
  });

  const handleChangeDirectly = (key, value) => {
    setValues({...values, [key]: value});
  }

  const handleSubmit = (event) => {
    event.preventDefault();
    let url = api + "recipes.php";
    let newIngredients = values.all_ingredients;
    const ingredient = document.getElementById("ingr_name1");
    const amount = document.getElementById("ingr_amt1");
    if(ingredient.value.length > 0) {
      newIngredients.push({ name: ingredient.value, amount: amount.value ? amount.value : 0 });
    }
		setValues({...values, all_ingredients: newIngredients});
    
    axios.post(url, values)
    .then(res => {
      if(res.data.status === 1) {
        history.push(`/recipes/${res.data.recipe_id}`);
      } else {
        // handle failure here
      }
    })
    .catch(err => console.log(err));

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
          <label htmlFor="prep_time">
            Prep time<sup>*</sup>:
            <span>In minutes</span>
          </label>
          <input 
            type="text" 
            id="prep_time" 
            name="prep_time"
            required
            value={values.prep_time}
            onChange={evt => handleChangeDirectly("prep_time", evt.target.value)}
          />

          <label htmlFor="cook_time">
            Cook time<sup>*</sup>:
            <span>In minutes</span>
          </label>
          <input 
            type="text" 
            id="cook_time" 
            name="cook_time" 
            required
            value={values.cook_time}
            onChange={evt => handleChangeDirectly("cook_time", evt.target.value)}
          />
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

}

export default AddRecipe;
