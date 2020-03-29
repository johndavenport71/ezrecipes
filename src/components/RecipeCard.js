import React from 'react';
import { Link } from 'react-router-dom';
import getRandomColor from '../utils/randomColor';

const RecipeCard = ({ recipe }) => {
  const root = process.env.REACT_APP_ROOT;
  const bgColor = getRandomColor();
  return (
    <Link to={`/recipe/${recipe.id}`} className="recipe-card" style={{
        backgroundImage: recipe.img_path ? 
        `url(${root + recipe.img_path})`
        :
        `url(${require('../assets/placeholder-transparent.png')})`,
        backgroundColor: bgColor
      }}>
      <div>
        <h3>{recipe.title.length > 30 ? recipe.title.substring(0, 30) + "..." : recipe.title}</h3>
        <p>{recipe.description && recipe.description.substring(0, 80) + "..."}</p>
      </div>
    </Link>
  );
}

export default RecipeCard;
