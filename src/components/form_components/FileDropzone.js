import React from 'react';

const FileDropzone = ({ values, setValues }) => {
  const handleChange = (value) => {
    setValues({...values, recipe_image: value})
  }

  return (
    <>
    <label htmlFor="recipe_img">Add an image</label>
    <input 
      type="file" 
      id="recipe_image" 
      name="recipe_image"
      onChange={e => handleChange(e.target.value)}
    />
    </>
  );
}

export default FileDropzone;
