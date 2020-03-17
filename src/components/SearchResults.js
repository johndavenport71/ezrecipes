import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import RecipeCard from './RecipeCard';

const SearchResults = () => {
  const { params } = useParams();
  const [recipes, setRecipes] = useState([]);
  const api = process.env.REACT_APP_API_PATH;

  useEffect(()=>{
    if(params.length !== 0) {
      let search = new URLSearchParams();
      search.append('search', params);
      const url = api + 'search.php?' + search.toString();
      console.log(url);
      axios.get(url)
      .then(res => {
        console.log(res)
        setRecipes(res.data.search.recipes);
      })
      .catch(err => console.log(err));
    }
  },[api, params]);

  return (
    <main>
      <h2>Results</h2>
      
      <p>Your search for '{params}' returned {recipes.length} results</p>
      {recipes && recipes.length > 0 && 
        <section className="recipes-grid">
          {recipes.map((recipe, i) => <RecipeCard recipe={recipe} key={i} />)}
        </section>
      }
    </main>
  );
}

export default SearchResults;
