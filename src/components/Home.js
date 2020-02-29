import React, { useEffect } from 'react';

const Home = ({ loggedIn, toggleLogin }) => {

  useEffect(()=>{
    console.log(loggedIn, toggleLogin);
  },[]);

  return (
    <main>
      <p>TO DO: Home page</p>
    </main>
  );
}

export default Home;
