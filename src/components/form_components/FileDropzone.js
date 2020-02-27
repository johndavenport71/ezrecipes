import React from 'react';

const FileDropzone = () => {
  return (
    <>
    <label htmlFor="recipe_img">Add an image</label>
    <input 
      type="file" 
      id="recipe_image" 
      name="recipe_image"
    />
    </>
  );
}

export default FileDropzone;
