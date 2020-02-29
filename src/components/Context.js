import React from 'react';

export const user = {
  loggedIn: false
}

export const UserContext = React.createContext(user.loggedIn);