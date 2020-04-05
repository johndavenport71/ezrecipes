import React from 'react';
import CardSection from './CardSection';
import FeaturedSection from './FeaturedSection';
import featured from '../utils/featuredCategories';
import AddCTA from './AddCTA';
const Home = () => {
  
  return (
    <>
    <FeaturedSection />
    <AddCTA />
    <main>
      {featured.map((row, i) => (
        <CardSection title={row.title} category={row.category} key={i} />
      ))}
    </main>
    </>
  );
}

export default Home;
