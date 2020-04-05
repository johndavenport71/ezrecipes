import React from 'react'
import { Link } from 'react-router-dom';

const AddCTA = () => {
  return (
    <div className="cta">
      <h2>Add your recipe to the mix!</h2>
      <Link to="/add-recipe" className="button">
        Add Recipe
      </Link>
    </div>
  )
}

export default AddCTA
