import React from 'react';

const Ingredients = ({ ingredients }) => {
  return (
    <ul>
      {ingredients.map((ingr, i) => {
        return (
          <li key={i}>
            {`${ingr.amount_desc} ${ingr.ingredient_name}`}
          </li>
        );
      })}
    </ul>
  );
}

export default Ingredients;