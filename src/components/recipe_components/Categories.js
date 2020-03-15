import React from 'react';
import upperCaseFirst from '../../utils/upperCase';

const Categories = ({ categories }) => {
  return (
    <section className="categories">
      {categories && categories.map((cat, i) => <a href={`/recipes/${cat.replace(/&amp;/g, '&')}`} className="category" key={i}>{upperCaseFirst(cat.replace(/&amp;/g, '&'))}</a>)}
    </section>
  );
}

export default Categories;
