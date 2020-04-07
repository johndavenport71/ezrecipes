import React from 'react';
import { Link } from 'react-router-dom';
import getRandomColor from '../utils/randomColor';

const RecipeCard = ({ recipe, category }) => {
  const bgColor = getRandomColor();
  const route = category ? category + `/recipe/${recipe.id}` : `/recipe/${recipe.id}`;
   
  return (
    <Link to={route} className="recipe-card" style={{
        backgroundImage: recipe.img_path ? 
        `url(${recipe.img_path.includes('http') ? recipe.img_path : `https://${recipe.img_path}`})`
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
