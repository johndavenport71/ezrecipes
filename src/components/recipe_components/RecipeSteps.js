import React from 'react';
import upperCaseFirst from '../../utils/upperCase';

const RecipeSteps = ({ steps }) => {
  return (
    <ol>
      {steps.map((step, i) => {
        if(step !== "") {
          return <li key={i}>{upperCaseFirst(step)}</li>
        }
      })}
    </ol>
  );
}

export default RecipeSteps;
