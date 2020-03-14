import React from 'react';
import FileDropzone from './FileDropzone';
import IngredientsInput from './IngredientsInput';

const RecipeForm = ({ values, setValues, handleChangeDirectly, handleSubmit }) => {
  return (
    <form id="add-recipe" onSubmit={handleSubmit} encType="multipart/form-data">
      <div className="half-width">
        <label htmlFor="recipe_title">What do you call your recipe?<sup>*</sup>:</label>
        <input 
          type="text" 
          id="recipe_title" 
          name="recipe_title" 
          required
          value={values.title}
          onChange={evt => handleChangeDirectly("title", evt.target.value)}
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
  );
}

export default RecipeForm;
