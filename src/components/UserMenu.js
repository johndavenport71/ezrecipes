import React from 'react';
import { Link, useHistory } from 'react-router-dom';

const UserMenu = ({ user, closeMenu }) => {
  const history = useHistory();

  const logout = (e) => {
    e.preventDefault();
    window.sessionStorage.removeItem('user');
    history.push('/', { loggedIn: false });
  }

  return (
    <ul onMouseLeave={closeMenu}>
      <li><Link to={`/user/${user.user_id}`}>Profile</Link></li>
      <li><Link to={`/user/${user.user_id}`}>My Recipes</Link></li>
      <li><button onClick={e => logout(e)}>Logout</button></li>
    </ul>
  );
}

export default UserMenu;
