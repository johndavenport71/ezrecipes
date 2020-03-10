import React, { useState } from 'react';
import validateEmail from '../utils/validateEmail';
import Errors from './Errors';
import { useHistory } from 'react-router-dom';
import axios from 'axios';

const Login = () => {
  const [values, setValues] = useState({
    email: "",
    password: "",
  });
  const [errors, setErrors] = useState([]);
  const history = useHistory();
  const api = process.env.REACT_APP_API_PATH;

  const handleChangeDirectly = (key, value) => {
    setValues({...values, [key]: value});
  }

  return (
    <main>
      <h1>Login</h1>
      {errors.length > 0 && <Errors errors={errors} />}
      <form id="login-form" className="user" onSubmit={handleSubmit}>
        <label htmlFor="email">Email Address</label>
        <input 
          id="email"
          name="email"
          type="email"
          required
          value={values.email}
          onChange={event => handleChangeDirectly("email", event.target.value)}
        />
        <input 
          id="password"
          name="password"
          type="password"
          required
          value={values.password}
          onChange={event => handleChangeDirectly("password", event.target.value)}
        />
        <input type="submit" value="login" />
      </form>
    </main>
  );

  function handleSubmit(event) {
    event.preventDefault();
    const errMsgs = [];
    if(values.email.length === 0 || values.password.length === 0) {
      errMsgs.push("Email and password are required");
    } else if (!validateEmail(values.email)) {
      errMsgs.push("Please enter a valid email");
    }
    if(errMsgs.length > 0) {
      setErrors(errMsgs);
    } else {
      const url = api + 'auth.php';
      const data = new FormData(event.target);
      axios.post(url, data)
      .then(res => { 
        console.log(res);
        if(res.data.status !== 1) {
          errMsgs.push(res.data.status_message);
          setErrors(errMsgs);
        } else {
          const user = res.data.user.data;
          //use sessionStorage to store user session
          sessionStorage.setItem('user', JSON.stringify(user));
          history.push(`/user/${user.user_id}`);
        }
        
      })
      .catch(err => console.log(err));
    }
  }

}

export default Login;

