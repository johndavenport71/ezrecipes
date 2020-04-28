import React, { useEffect, useState } from 'react';
import axios from 'axios';

const CategoryList = () => {
  const api = process.env.REACT_APP_API_PATH;
  const [categories, setCategories] = useState([]);

  useEffect(()=>{
    axios.get(api + "categories.php")
    .then(res => {
      console.log(res);
      setCategories(res.data.categories);
    })
    .catch(err => console.log(err));
  },[]);

  return (
    <main>
      <h2>All categories</h2>
      {categories.length > 0 &&
        <ul className="category-list">
          {categories.map(category => (
            <li key={category.category_id}>
              <a href={`/recipes/${encodeURIComponent(category.category_desc).replace(/(&amp;)|(\%26)/g, '&')}`}>{category.category_desc}</a>
            </li>
          ))}

        </ul>
      }
    </main>
  )
}

export default CategoryList;
