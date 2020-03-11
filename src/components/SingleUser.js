import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import RecipeCard from './RecipeCard';

const SingleUser = () => {
  const { id } = useParams();
  const api = process.env.REACT_APP_API_PATH;
  const [user, setUser] = useState({});
  const [recipes, setRecipes] = useState([]);
  const session = JSON.parse(window.sessionStorage.getItem('user'));

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

    const fetchRecipes = (api, id, setRecipes) => {
      const url = api + "recipes.php?user=" + id;
      fetch(url)
      .then(res => res.json())
      .then(res => {
        setRecipes(res.data);
      })
      .catch(err => console.log(err))
    }
    fetchRecipes(api, id, setRecipes);

  },[api, id]);

  return (
    <main>
      {user && user.first_name && <h1>{user.display_name ? user.display_name : user.first_name + " " + user.last_name}</h1>}
      {user && user.profile_pic ? 
        <img 
          src={`http://localhost:8888/ezrecipes/${user.profile_pic}`} 
          alt={user.display_name ? (`Picture of ${user.display_name}`) : (`Picture of ${user.first_name} ${user.last_name}`)}
          width="100"
          height="100"
        /> 
        : 
        <img
          src={require('../assets/icons/happy_face.svg')}
          alt="smiley face icon"
          width="100"
          height="100"
        />
      }
      {recipes.length > 0 &&
        <>
        <h2>Recipes by this user:</h2>
        <section className="user-recipes">
          {recipes.map((recipe, i) => <RecipeCard recipe={recipe} key={i} />)}
        </section>
        </>
      }
    </main>
  );
}

export default SingleUser;
