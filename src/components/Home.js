import React from 'react';
import CardSection from './CardSection';

const Home = ({ loggedIn, toggleLogin }) => {
  
  return (
    <main>
      <CardSection title="Italian Recipes" category="italian" />
      <CardSection title="Asian Recipes" category="asian" />
      <CardSection title="Fast Recipes" category="fast" />
    </main>
  );
}

export default Home;
