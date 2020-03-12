import React, { useState } from 'react';
import RecipeCard from './RecipeCard';
import SliderArrows from './SliderArrows';

const CardContainer = ({ recipes }) => {
  const [transform, setTransform] = useState(0);

  const handleClick = (dir) => {
    const newTransform = document.querySelector(".inner-container").clientWidth;
    const totalWidth = document.querySelector(".inner-container").scrollWidth;
    if(dir === "left" && transform !== 0) {
      setTransform(transform + newTransform);
    } else if (dir === "right" && transform - newTransform > -totalWidth) {
      setTransform(transform - newTransform);
    }
  }

  return (
    <div className="card-container">
      {recipes.length > 4 &&
        <SliderArrows handleClick={handleClick} setTransform={setTransform} transform={transform} />
      }
      <div className="inner-container" style={{transform: `translateX(${transform}px)`}}>
        {recipes.map(recipe => <RecipeCard key={recipe.id} recipe={recipe} />)}
      </div>
    </div>
  );
}

export default CardContainer;
