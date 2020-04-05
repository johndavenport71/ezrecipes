import React from 'react'
import { Link } from 'react-router-dom'

const Breadcrumb = ({ category }) => {
  return (
    <Link to={`/recipes/${category}`} className="breadcrumb">
      View more{' ' + category + ' '}recipes
    </Link>
  )
}

export default Breadcrumb
