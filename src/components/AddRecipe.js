import React, { useState } from 'react';
import FileDropzone from './form_components/FileDropzone';
import IngredientsInput from './form_components/IngredientsInput';

/*

**TO DO: FORM SUBMISSION**

*/
const AddRecipe = () => {

  const api = process.env.REACT_APP_API_PATH;

  const [values, setValues] = useState({
    recipe_title: "",
    description: "",
    steps: "",
    prep_time: 0,
    cook_time: 0,
    categories: "",
    all_ingredients: []
  });

  const handleChangeDirectly = (key, value) => {
    setValues({...values, [key]: value});
  }

  const handleSubmit = (event) => {
    event.preventDefault();
    const data = new FormData(event.target);
    const url = api + "recipes.php";
    console.log(JSON.stringify(...data));
    // fetch(url, {
    //   method: "POST",
    //   body: JSON.stringify(data)
    // }).then(res => {return res.json()})
    // .then(res => console.log(res))
    // .catch(err => console.log(err));
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
          <FileDropzone />
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
