import React from 'react';
import FileDropzone from './form_components/FileDropzone';
import IngredientsInput from './form_components/IngredientsInput';

/*

**TO DO: FORM SUBMISSION**

*/
const AddRecipe = () => {

  const handleSubmit = (event) => {
    event.preventDefault();
    const data = new FormData(event.target);
    console.log(...data);
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
          />

          <label htmlFor="description">Tell us a little about it<sup>*</sup>:</label>
          <textarea 
            id="description" 
            name="description" 
            maxLength="255" 
            rows="8" 
            cols="50" 
            required
          ></textarea>
        </div>

        <div className="half-width">
          <FileDropzone />
        </div>

        <IngredientsInput />

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
          ></textarea>
        </div>
        <input type="submit" value="Submit" />
      </form>
    </main>
  );
}

export default AddRecipe;
