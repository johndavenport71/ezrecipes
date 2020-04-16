import React, { useState, useEffect } from 'react';
import { useParams, useHistory } from 'react-router-dom';
import RecipeCard from './RecipeCard';
import SavedRecipes from './SavedRecipes';
import Modal from './Modal';
import axios from 'axios';

const SingleUser = () => {
  const { id } = useParams();
  const history = useHistory();
  const api = process.env.REACT_APP_API_PATH;
  const root = process.env.REACT_APP_ROOT;
  const [open, setOpen] = useState(false);
  const [deleteSuccess, setDeleteSuccess] = useState(false);
  const [user, setUser] = useState({});
  const [recipes, setRecipes] = useState([]);
  const session = JSON.parse(window.sessionStorage.getItem('user'));

  useEffect(()=>{
    if(!session || session.uuid !== id && session.member_level != 'a') {
      history.push('/');
    }
    const fetchRecipes = (api, id, setRecipes) => {
      const url = api + "recipes.php?user=" + id;
      fetch(url)
      .then(res => res.json())
      .then(res => {
        setRecipes(res.data);
      })
      .catch(err => console.log(err))
    }

    const fetchUser = (api, id, setUser) => {
      const url = api + "users.php?id=" + id;
      fetch(url).then(res => res.json())
        .then(res => {
          console.log(res);
          if(res.status === 1) {
            setUser(res.data);
            fetchRecipes(api, res.data.user_id, setRecipes);
          } else {
            history.push('/');
          }
        });
    }
    fetchUser(api, id, setUser);

    //eslint-disable-next-line
  },[api, id]);

  const handleDelete = (evt) => {
    evt.preventDefault();
    const url = api + 'users.php?id=' + user.user_id;
    axios.delete(url)
    .then(res => {
      if(res.data.status == 1) {
        setDeleteSuccess(1);
        window.sessionStorage.removeItem('user');
      } else {
        //to do
      }
    })
    .catch(err => console.log(err));
  }

  return (
    <main>
      <div className="two-column">
        <div>
          {user && user.first_name && <h1>{user.display_name ? user.display_name : user.first_name + " " + user.last_name}</h1>}
          {user && user.profile_pic ? 
            <img 
              src={root + user.profile_pic} 
              alt={user.display_name ? (`${user.display_name}`) : (`${user.first_name} ${user.last_name}`)}
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
        </div>
        {session.uuid !== id ? 
        <div className="admin-user-actions">
          <button className="secondary-button warning" onClick={()=>{setOpen(true)}}>Delete User</button>
        </div>
        :
        <div className="user-actions">
          <h2>Want to personalize your profile?</h2>
          <a href={`/user/edit/${id}`}>Edit Profile</a>
          <h2>Want to add a recipe?</h2>
          <a href="/add-recipe">Add a recipe</a>
        </div>
        }
      </div>
      {session && session.uuid === id &&
        <SavedRecipes id={session.user_id} />
      }
      {recipes && recipes.length > 0 &&
        <>
        <h2>{session.uuid !== id ? 'User' : 'Your'} submitted recipes</h2>
        <section className="recipes-grid">
          {recipes.map((recipe, i) => <RecipeCard recipe={recipe} key={i} />)}
        </section>
        </>
      }
      {open &&
        <Modal>
          <div>
            {deleteSuccess === 1 ?
            <>
            <h2>Account Deleted</h2>
            <a href="/">Return to home page</a>
            </>  
            :
            <>
            <h2>Are you sure you want to delete this user?</h2>
            <button className="secondary-button" onClick={() => setOpen(false)}>Cancel</button>
            <button className="secondary-button warning" onClick={handleDelete}>Delete User</button>
            </>
          }
          </div>
        </Modal>
      }
    </main>
  );
}

export default SingleUser;
