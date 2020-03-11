import React from 'react';
import { Link } from 'react-router-dom';
import SearchField from './SearchField';
import UserHeader from './UserHeader';

const Header = () => {
  const session = JSON.parse(window.sessionStorage.getItem('user'));

  return (
    <header>
      <h1>
        <Link to="/">
          EZ 
          <img src={require("../assets/chef.svg")} alt="Chef outline icon" className="chef-icon" width="50" height="50"></img>
          Recipes
        </Link>
      </h1>
      <div>
        {session && session.user_id ?
          <UserHeader user={session} />
        :
          <>
          <SearchField />
          <Link to="/sign-up" className="button">Sign Up</Link>
          <Link to="/login">Login</Link>
          </>
        }
      </div>
    </header>
  );
}

export default Header;
