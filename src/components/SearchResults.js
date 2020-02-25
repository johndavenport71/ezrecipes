import React from 'react';
import { useParams } from 'react-router-dom';

const SearchResults = () => {
  const { params } = useParams();

  return (
    <main>
      <p>TO DO: search results</p>
      <p>{params}</p>
    </main>
  );
}

export default SearchResults;
