import React from 'react';
import upperCaseFirst from '../../utils/upperCase';

const Categories = ({ categories }) => {
  return (
    <section className="categories">
      {categories && categories.map((cat, i) => 
        //eslint-disable-next-line
        <a href={`/recipes/${encodeURIComponent(cat).replace(/(&amp;)|(\%26)/g, '&')}`} className="category" key={i}>
          {upperCaseFirst(cat.replace(/&amp;/g, '&'))}
        </a>
      )}
    </section>
  );
}

export default Categories;
