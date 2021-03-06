import React from 'react';
import upperCaseFirst from '../../utils/upperCase';

const Ingredients = ({ ingredients }) => {
  return (
    <ul className="ingredients-list">
      {ingredients.map((ingr, i) => {
        return (
          <li key={i}>
            {`${upperCaseFirst(ingr)}`}
          </li>
        );
      })}
    </ul>
  );
}

export default Ingredients;