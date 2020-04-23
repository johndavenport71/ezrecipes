import React from 'react';
import RecipeCard from './Global/RecipeCard';

class RecipeCardList extends React.PureComponent {
  render() {
    return(
      <>
      {this.props.recipes.map(recipe => <RecipeCard key={recipe.id} recipe={recipe} />)}
      </>
    );
  }
}

export default RecipeCardList;
