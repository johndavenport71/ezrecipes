import React from 'react';
import { Link } from 'react-router-dom';

const RecipeCard = ({ recipe }) => {
  return (
    <Link to={`/recipe/${recipe.id}`} className="recipe-card" style={{
        backgroundImage: recipe.img_path ? 
        `url(http://localhost:8888/ezrecipes/${recipe.img_path})`
        :
        `url(${require('../assets/placeholder-image.png')})`
      }}>
      <div>
        <h3>{recipe.title}</h3>
        <p>{recipe.description}</p>
      </div>
    </Link>
  );
}

export default RecipeCard;
