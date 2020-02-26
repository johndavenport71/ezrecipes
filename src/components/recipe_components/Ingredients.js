import React from 'react';
import upperCaseFirst from '../../utils/upperCase';

const Ingredients = ({ ingredients }) => {
  return (
    <ul>
      {ingredients.map((ingr, i) => {
        return (
          <li key={i}>
            {`${ingr.amount_desc !== "0" ? ingr.amount_desc : ""} ${upperCaseFirst(ingr.ingredient_name)}`}
          </li>
        );
      })}
    </ul>
  );
}

export default Ingredients;