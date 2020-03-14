import React from 'react';
import { Link } from 'react-router-dom';

const RecipeCard = ({ recipe }) => {
  const root = process.env.REACT_APP_ROOT;
  return (
    <Link to={`/recipe/${recipe.id}`} className="recipe-card" style={{
        backgroundImage: recipe.img_path ? 
        `url(${root + recipe.img_path})`
        :
        `url(${require('../assets/placeholder-image.png')})`
      }}>
      <div>
        <h3>{recipe.title.length > 30 ? recipe.title.substring(0, 30) + "..." : recipe.title}</h3>
        <p>{recipe.description && recipe.description.substring(0, 80) + "..."}</p>
      </div>
    </Link>
  );
}

export default RecipeCard;
