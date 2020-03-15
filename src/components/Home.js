import React from 'react';
import CardSection from './CardSection';
import FeaturedSection from './FeaturedSection';
import featured from '../utils/featuredCategories';
const Home = () => {
  
  return (
    <>
    <FeaturedSection />
    <main>
      {featured.map((row, i) => (
        <CardSection title={row.title} category={row.category} key={i} />
      ))}
    </main>
    </>
  );
}

export default Home;
