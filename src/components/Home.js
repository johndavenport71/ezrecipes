import React from 'react';
import CardSection from './CardSection';

const Home = ({ loggedIn, toggleLogin }) => {
  
  return (
    <main>
      <CardSection title="Low Calorie Recipes" category="Low Cal" />
      <CardSection title="Healthy Recipes" category="Healthy" />
      <CardSection title="Vegan Recipes" category="vegan" />
    </main>
  );
}

export default Home;
