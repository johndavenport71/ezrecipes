import React, { useState } from 'react';
import { Redirect } from 'react-router-dom';

const SearchField = () => {
  const [query, setQuery] = useState("");
  const [submit, setSubmit] = useState(false);

  const handleChange = (event) => {
    setSubmit(false);
    setQuery(event.target.value);
  }
  
  const handleSubmit = (event) => {
    event.preventDefault();
    setSubmit(true);
  }

  return (
    <form className="search-bar" onSubmit={handleSubmit}>
      <input 
        type="search" 
        id="search" 
        name="search" 
        placeholder="Search" 
        value={query} 
        onChange={handleChange}
      />

      {submit && <Redirect to={{pathname: `/search/${query}`}} />}
    </form>
    
  );
}

export default SearchField;
