import React, { useEffect, useState } from 'react';
import { useParams, Redirect, useHistory } from 'react-router-dom';
import axios from 'axios';
import FileDropzone from '../form_components/FileDropzone';
import Alert from '../Global/Alert';
import Modal from '../Global/Modal';
import passwordCheck from '../../utils/passwordCheck';

const EditUser = () => {
  const session = JSON.parse(window.sessionStorage.getItem('user'));
  const history = useHistory();
  const api = process.env.REACT_APP_API_PATH;
  const { id } = useParams();
  const [open, setOpen] = useState(false);
  const [message, setMessage] = useState("");
  const [errors, setErrors] = useState([]);
  const [deleteSuccess, setDeleteSuccess] = useState(0);
  const [user, setUser] = useState({});
  const [values, setValues] = useState({
    first_name: user.first_name ? user.first_name : "",
    last_name: user.last_name ? user.last_name : "",
    display_name: user.display_name ? user.display_name : "",
    email: user.email ? user.email : "",
    image: user.profile_pic ? user.profile_pic : "",
    og_password: "",
    new_password: "",
    new_password_confirm: ""
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
  },[api, id, history]);

  return (
    <main>
      {!session || session.uuid !== id ? 
      <Redirect to={`/user/${id}`} /> 
      : 
      <>
      <h2>Edit User</h2>
      <form onSubmit={handleSubmit}>
        <label htmlFor="first_name">First Name</label>
        <input 
          type="text" 
          name="first_name" 
          id="first_name" 
          value={values.first_name} 
          onChange={e => handleChangeDirectly('first_name', e.target.value)} 
        />
        <label htmlFor="last_name">Last Name</label>
        <input 
          type="text" 
          name="last_name" 
          id="last_name" 
          value={values.last_name} 
          onChange={e => handleChangeDirectly('last_name', e.target.value)} 
        />
        <label htmlFor="display_name">Display Name</label>
        <input 
          type="text" 
          name="display_name" 
          id="display_name" 
          value={values.display_name} 
          onChange={e => handleChangeDirectly('display_name', e.target.value)} 
        />
        <label htmlFor="email">Email Address</label>
        <input 
          type="text" 
          name="email" 
          id="email" 
          value={values.email} 
          onChange={e => handleChangeDirectly('email', e.target.value)} 
        />
        <FileDropzone values={values} setValues={setValues} />
        <input type="submit" value="Save Changes" />
      </form>
      <div className="two-column">
        <div>
          <h3>Want to delete your account?</h3>
          <button aria-haspopup="dialog" className="secondary-button warning" onClick={(e) => handleClick(e, true)}>Delete Account</button>
        </div>
        <div>
          <h3>Change Password</h3>
          <form id="change-password" onSubmit={handlePassword}>
            <label htmlFor="og_password">Current Password</label>
            <input 
              type="password" 
              name="og_password"
              id="og_password"
              value={values.og_password} 
              onChange={e => handleChangeDirectly('og_password', e.target.value)} 
              required
            />
            <label htmlFor="new_password">New Password</label>
            <input 
              type="password" 
              name="new_password" 
              id="new_password" 
              value={values.new_password} 
              onChange={e => handleChangeDirectly('new_password', e.target.value)} 
              required
            />
            <label htmlFor="new_password_confirm">Confirm New Password</label>
            <input 
              type="password" 
              name="new_password_confirm" 
              id="new_password_confirm" 
              value={values.new_password_confirm} 
              onChange={e => handleChangeDirectly('new_password_confirm', e.target.value)} 
              required
            />
            <input type="submit" value="Change Password" />
          </form>
        </div>
      </div>
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
            <button className="secondary-button warning" onClick={handleDelete}>Yes, delete my account</button>
            <button className="secondary-button" onClick={(e) => handleClick(e, false)}>No, thank you</button>
            </>
            }
          </div>
        </Modal>
      }
      {message && <Alert message={message} setOpen={() => setMessage("")} />}
      {errors.length > 0 && <Alert errors={errors} setOpen={() => setErrors([])} />}
    </main>
  );

  function handleDelete(evt) {
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
        setMessage("Information updated!");
      } else {
        console.log(res);
      }
    })
    .catch(err => console.log(err));
  }

  function handlePassword(evt) {
    evt.preventDefault();
    if(passwordCheck(values.new_password)) {
      let params = new FormData();
      params.append('user_id', user.user_id);
      params.append('og_password', values.og_password);
      params.append('new_password', values.new_password);
      params.append('new_password_confirm', values.new_password_confirm);
      const url = api + 'change-password.php';
      axios.post(url, params)
      .then(res => {
        console.log(res);
        if(res.data.status === 1) {
          setMessage(res.data.status_message);
          setValues({...values, og_password: "", new_password: "", new_password_confirm: ""});
        } else {
          setErrors([res.data.status_message]);
        }
      })
      .catch(err => console.log(err));
    } else {
      setErrors(["Your password must contain at least 8 characters, a number and a special character."]);
    }
  }
}

export default EditUser;
