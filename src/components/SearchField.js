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
    if(query !== "") {
      setSubmit(true);
    }
  }

  return (
    <form className="search-bar" onSubmit={handleSubmit}>
      <label htmlFor="search">Search</label>
      <input 
        type="search"
        id="search" 
        name="search" 
        placeholder="Search" 
        value={query} 
        onChange={handleChange}
      />
      <button type="submit" className="search-button" aria-label="search">
        <img src={require('../assets/icons/search.svg')} alt="" width="25" height="25" />
      </button>
      {submit && <Redirect to={{pathname: `/search/${query}`}} />}
    </form>
    
  );
}

export default SearchField;
