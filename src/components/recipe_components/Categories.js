import React from 'react';
import upperCaseFirst from '../../utils/upperCase';

const Categories = ({ categories }) => {
  return (
    <section className="categories">
      {categories && categories.map((cat, i) => <a href={`/recipes/${cat}`} className="category" key={i}>{upperCaseFirst(cat)}</a>)}
    </section>
  );
}

export default Categories;
