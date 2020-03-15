import React from 'react';

const FileDropzone = ({ values, setValues }) => {
  const handleChange = (value) => {
    setValues({...values, image: value})
  }

  return (
    <>
    <label htmlFor="image">Add an image</label>
    <input 
      type="file" 
      id="image" 
      name="image"
      onChange={e => handleChange(e.target.value)}
    />
    </>
  );
}

export default FileDropzone;
