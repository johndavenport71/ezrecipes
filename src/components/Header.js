import React from 'react';
import { Link } from 'react-router-dom';
import SearchField from './SearchField';
import UserHeader from './UserHeader';

const Header = () => {
  const session = JSON.parse(window.sessionStorage.getItem('user'));

  return (
    <header>
      <Link to="/">
        <img src={require("../assets/logo.png")} alt="Ez Recipes" className="logo" />
      </Link>
      <div>
        <SearchField />
        {session && session.user_id ?
          <UserHeader user={session} />
        :
          <>
          <Link to="/sign-up" className="button">Sign Up</Link>
          <Link to="/login">Login</Link>
          </>
        }
      </div>
    </header>
  );
}

export default Header;
