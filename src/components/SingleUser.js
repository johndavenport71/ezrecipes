import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';

const SingleUser = () => {
  const { id } = useParams();
  const api = process.env.REACT_APP_API_PATH;
  const [user, setUser] = useState({});

  useEffect(()=>{
    const fetchUser = (api, id, setUser) => {
      const url = api + "users.php?id=" + id;
      fetch(url).then(res => res.json())
        .then(res => {
          console.log(res);
          if(res.status === 1) {
            setUser(res.data);
          }
        });
    }
    fetchUser(api, id, setUser);
  },[api, id]);

  return (
    <main>
      {user && <h1>{user.display_name ? user.display_name : user.first_name + " " + user.last_name}</h1>}
      {user && user.profile_pic ? 
        <img 
          src={`http://localhost:8888/ezrecipes/${user.profile_pic}`} 
          alt={user.display_name ? (`Picture of ${user.display_name}`) : (`Picture of ${user.first_name} ${user.last_name}`)}
          width="100"
          height="100"
        /> 
        : 
        ""
      }
    </main>
  );
}

export default SingleUser;
