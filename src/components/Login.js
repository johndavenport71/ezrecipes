import React, { useState } from 'react';
import validateEmail from '../utils/validateEmail';
import Errors from './Errors';
import { useHistory, Redirect } from 'react-router-dom';

const Login = ({ loggedIn, toggleLogin }) => {
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

  const handleSubmit = (event) => {
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
      fetch(url, {
        method: "POST",
        body: data
      }).then(res=>res.json()).then(res => {
        if(res.status === 0) {
          errMsgs.push(res.status_message);
          setErrors(errMsgs);
        } else {
          toggleLogin();
          history.push(`/user/${res.user_id}`);
        }
      });
    }
  }

  return (
    <main>
      {loggedIn ? 
        <Redirect to="/" />  
        :
        <>
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
        </>
      }
      
    </main>
  );

}

export default Login;

