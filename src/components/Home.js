import React from 'react';
import CardSection from './Global/CardSection';
import FeaturedSection from './Global/FeaturedSection';
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
      <aside className="cta">
        <h2>Looking for something different?</h2>
        <a href="/categories">Explore all categories</a>
      </aside>
    </main>
    </>
  );
}

export default Home;
