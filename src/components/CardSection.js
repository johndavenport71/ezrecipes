import React, { useState, useEffect } from 'react';
import LoadingCard from './LoadingCard';
import CardContainer from './CardContainer';

const CardSection = ({ title, category }) => {

  const api = process.env.REACT_APP_API_PATH;
  const [recipes, setRecipes] = useState([]);

  useEffect(()=>{
    const fetchRecipes = (api) => {
      let url = api + 'categories.php?';
      let params = new URLSearchParams();
      params.append('categories', encodeURI(category));
      url += params.toString();
      fetch(url).then(res=>res.json()).then(res=>{
        setRecipes(res.data);
      }).catch(e=>console.log(e));
    }
    fetchRecipes(api);
    // eslint-disable-next-line
  },[api]);


  return (
    <section>
      <div className="section-heading">
        <h2>{title}</h2>
        <a href={`/recipes/${category}`}>View More</a>
      </div>
      {recipes && recipes.length > 0 ?
        <CardContainer recipes={recipes} />
        :
        <LoadingCard />
      }
    </section>
  );
}

export default CardSection;