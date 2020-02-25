import React from 'react';
import { Link } from 'react-router-dom';

const Header = () => {
  return (
    <header>
      <h1>
        <Link to="/">EZ Recipes</Link>
      </h1>
      <div>
        <input type="search" id="search" name="search" placeholder="Search" />
        <Link to="/sign-up" className="button">Sign Up</Link>
        <Link to="/login">Login</Link>
      </div>
    </header>
  );
}

export default Header;
