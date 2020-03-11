import React, { useState } from 'react';
import UserMenu from './UserMenu';

const UserHeader = ({ user }) => {

  const [open, setOpen] = useState(false);

  const showMenu = (e) => {
    console.log(e.currentTarget);
    setOpen(true);
  }

  const closeMenu = () => {
    setOpen(false);
  }

  return (
    <div className="user-header" onClick={showMenu} onKeyPress={showMenu} aria-haspopup="menu" role="button" tabIndex="0">
    {user.profile_pic ? 
    <p>todo</p>
    :
    <img src={require('../assets/icons/happy_face.svg')} className="user-image" alt="smiley face icon" />
    }
    <p>Hi there, {user.display_name ? user.display_name : user.first_name}</p>
    <img src={require('../assets/icons/arrow_drop_down.svg')} alt="" />
    {open && <UserMenu user={user} closeMenu={closeMenu} />}
    </div>
  );
}

export default UserHeader;
