import React from 'react';
import { Link } from 'react-router-dom';
import SearchField from './SearchField';
import UserHeader from '../User/UserHeader';
import useWindowDimensions from '../../utils/windowDimensions';

const Header = () => {
  const session = JSON.parse(window.sessionStorage.getItem('user'));
  const { width } = useWindowDimensions();

  return (
    <header>
      {width > 960 ?
      <h1>
        <Link to="/">
          <img src={require("../../assets/logo.png")} alt="Ez Recipes" className="logo" />
        </Link>
      </h1>
      :
      <h1>
        <Link to="/">
          <img src={require("../../assets/chef.svg")} alt="Ez Recipes" className="logo" />
        </Link>
      </h1>
      }
      <div>
        {width > 600 &&
          <SearchField />
        }
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
