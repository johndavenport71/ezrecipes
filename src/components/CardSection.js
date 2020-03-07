import React, { useState, useEffect, lazy, Suspense } from 'react';
import LoadingCard from './LoadingCard';
const CardContainer = lazy(()=> import('./CardContainer'));

const CardSection = ({ title, category }) => {

  const api = process.env.REACT_APP_API_PATH;
  const [recipes, setRecipes] = useState([]);

  useEffect(()=>{
    const fetchRecipes = (api) => {
      const url = api + 'categories.php?categories=' + category;
      fetch(url).then(res=>res.json()).then(res=>{
        console.log(res);
        setRecipes(res.data);
      }).catch(e=>console.log(e));
    }
    fetchRecipes(api);
    // eslint-disable-next-line
  },[api]);


  return (
    <section>
      <h2 className="section-heading">{title}</h2>
      <Suspense fallback={<LoadingCard />}>
      {recipes && recipes.length > 0 &&
        <CardContainer recipes={recipes} />
      }
      </Suspense>
    </section>
  );
}

export default CardSection;