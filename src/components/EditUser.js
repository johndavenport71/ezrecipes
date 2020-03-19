import React, { useEffect, useState } from 'react';
import { useParams, Redirect, useHistory } from 'react-router-dom';
import axios from 'axios';
import FileDropzone from './form_components/FileDropzone';
import Modal from './Modal';

const EditUser = () => {
  const session = JSON.parse(window.sessionStorage.getItem('user'));
  const history = useHistory();
  const api = process.env.REACT_APP_API_PATH;
  const root = process.env.REACT_APP_ROOT;
  const { id } = useParams();
  const [open, setOpen] = useState(false);
  const [deleteSuccess, setDeleteSuccess] = useState(0);
  const [user, setUser] = useState({});
  const [values, setValues] = useState({
    first_name: user.first_name ? user.first_name : "",
    last_name: user.last_name ? user.last_name : "",
    display_name: user.display_name ? user.display_name : "",
    email: user.email ? user.email : "",
    image: user.profile_pic ? user.profile_pic : ""
  });

  const handleChangeDirectly = (key, value) => {
    setValues({...values, [key]: value});
  }
  
  const handleClick = (e, openState) => {
    e.preventDefault();
    setOpen(openState);
  }

  useEffect(()=>{
    const fetchUser = (api, id) => {
      const url = api + 'users.php?id=' + id;
      axios.get(url)
      .then(res => {
        console.log(res)
        if(res.data.status == 1) {
          setUser(res.data.data);
          setValues({
            first_name: res.data.data.first_name ?? "",
            last_name: res.data.data.last_name ?? "",
            display_name: res.data.data.display_name ?? "",
            email: res.data.data.email ?? "",
            image: res.data.data.profile_pic ?? ""
          });
        } else {
          history.push('/');
        }
      })
      .catch(err => console.log(err));
    }
    fetchUser(api, id);
  },[api, id]);

  return (
    <main>
      {!session || session.user_id !== id ? 
      <Redirect to={`/user/${id}`} /> 
      : 
      <>
      <h2>{user && user.display_name ? user.display_name : user.first_name + ' ' + user.last_name}</h2>
      <form onSubmit={handleSubmit}>
        <label htmlFor="first_name">First Name</label>
        <input 
          type="text" 
          name="first_name" 
          value={values.first_name} 
          onChange={e => handleChangeDirectly('first_name', e.target.value)} 
        />
        <label htmlFor="last_name">Last Name</label>
        <input 
          type="text" 
          name="last_name" 
          value={values.last_name} 
          onChange={e => handleChangeDirectly('last_name', e.target.value)} 
        />
        <label htmlFor="display_name">Display Name</label>
        <input 
          type="text" 
          name="display_name" 
          value={values.display_name} 
          onChange={e => handleChangeDirectly('display_name', e.target.value)} 
        />
        <label htmlFor="email">Email Address</label>
        <input 
          type="text" 
          name="email" 
          value={values.email} 
          onChange={e => handleChangeDirectly('email', e.target.value)} 
        />
        <FileDropzone values={values} setValues={setValues} />
        <input type="submit" value="Save Changes" />
      </form>
      <h2>Want to delete your account?</h2>
      <button aria-haspopup="dialog" className="secondary-button" onClick={(e) => handleClick(e, true)}>Delete Account</button>
      </>
      }
      {open && 
        <Modal>
          <div>
            {deleteSuccess === 1 ?
            <>
            <h3>Your account was successfully deleted</h3>
            <a href="/">Return to home page</a>
            </> 
            :
            <>
            <h3>Are you sure you want to delete your account?</h3>
            <button className="secondary-button" onClick={handleDelete}>Yes, delete my account</button>
            <button className="secondary-button" onClick={(e) => handleClick(e, false)}>No, thank you</button>
            </>
            }
          </div>
        </Modal>
      }
    </main>
  );

  function handleDelete(evt) {
    evt.preventDefault();
    const url = api + 'users.php?id=' + id;
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

  function handleSubmit(evt) {
    evt.preventDefault();
    const url = api + 'users.php';
    const files = document.getElementById('image');
    let file;
    if(files.files) {
      file = files.files[0];
    }

    let params = new FormData();
    params.append('user_id', user.user_id);
    params.append('first_name', values.first_name);
    params.append('last_name', values.last_name);
    params.append('display_name', values.display_name);
    params.append('email', values.email);
    params.append('image', file);

    axios.put(url, params)
    .then(res => {
      if(res.data.status === 1) {
        const data = JSON.stringify(res.data.user.data);
        window.sessionStorage.setItem('user', data);
      } else {
        console.log(res);
      }
    })
    .catch(err => console.log(err));
  }
}

export default EditUser;
