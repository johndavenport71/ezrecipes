import React, { useState } from 'react';
import signupValidator from '../utils/signupValidator';
import passwordCheck from '../utils/passwordCheck';
import validateEmail from '../utils/validateEmail';
import Alert from './Alert';
import { useHistory } from 'react-router-dom';
import axios from 'axios';

const SignUp = () => {
  const history = useHistory();
  const [values, setValues] = useState({
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    password_confirm: "",
    newsletter: "",
    user_verify: ""
  });
  const [errors, setErrors] = useState([]);
  const api = process.env.REACT_APP_API_PATH;

  const handleChangeDirectly = (key, value) => {
    setValues({...values, [key]: value});
  }

  const checkEmail = () => {
    if(validateEmail(values.email)) {
      const url = api + 'auth.php?email=' + values.email;
      axios.get(url)
      .then(res => {
        if(res.data.email !== false) {
          const error = "A user with that email already exists";
          if(errors.length > 0) {
            let allErrors = errors;
            allErrors.push(error);
            setErrors(allErrors);
          } else {
            setErrors([error]);
          }
        } else {
          if(errors.length === 0) {
            setErrors([]);
          }
        }
      })
      .catch(err => console.log(err));
    } else {
      const error = "Please enter a valid email address";
      if(errors.length > 0 && !errors.includes("Please enter a valid email address")) {
        let allErrors = errors;
        allErrors.push(error);
        setErrors(allErrors);
      } else {
        setErrors([error]);
      }
    }
  }

  const checkPasswords = () => {
    if(values.password !== values.password_confirm) {
      const error = "Passwords do not match";
      if(errors.length > 0 && !errors.includes("Passwords do not match")) {
        let allErrors = errors;
        allErrors.push(error);
        setErrors(allErrors);
      } else {
        setErrors([error]);
      }
    } else if (passwordCheck(values.password) === null) {
      const error = "Your password must contain at least 8 characters, a number and a special character.";
      if(errors.length > 0 && !errors.includes("Your password must contain at least 8 characters, a number and a special character.")) {
        let allErrors = errors;
        allErrors.push(error);
        setErrors(allErrors);
      } else {
        setErrors([error]);
      }
    } else {
      setErrors([]);
    }
  }

  
  return (
    <main>
      <h1>Sign Up</h1>
      
      {errors.length > 0 && <Alert errors={errors} setOpen={() => setErrors([])} />}

      <form id="sign-up" className="user" onSubmit={handleSubmit}>
        <label htmlFor="first_name">First Name</label>
        <input 
          type="text" 
          id="first_name" 
          name="first_name" 
          value={values.first_name} 
          onChange={event => handleChangeDirectly("first_name", event.target.value)} 
          required
          />
        <label htmlFor="last_name">Last Name</label>
        <input 
          type="text" 
          id="last_name" 
          name="last_name" 
          value={values.last_name}
          onChange={event => handleChangeDirectly("last_name", event.target.value)}
          required 
          />
        <label htmlFor="email">Email Address</label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          value={values.email}
          onChange={event => handleChangeDirectly("email", event.target.value)}
          onBlur={checkEmail}
          required 
          />
        <label htmlFor="password">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password"
          value={values.password}
          onChange={event => handleChangeDirectly("password", event.target.value)}
          required 
          />
        <label htmlFor="password_confirm">Confirm Password</label>
        <input 
          type="password" 
          id="password_confirm" 
          name="password_confirm"
          value={values.password_confirm}
          onChange={event => handleChangeDirectly("password_confirm", event.target.value)}
          onBlur={checkPasswords}
          required 
          />
        <input 
          type="text" 
          id="newsletter" 
          name="newsletter" 
          autoComplete="off" 
          tabIndex="-1" 
          aria-hidden="true"
          value={values.newsletter}
          onChange={event => handleChangeDirectly("newsletter", event.target.value)}
          />
        <label htmlFor="user_verify">A little test to prove that you're not a bot. What is this website about?</label>
        <input 
          type="text" 
          id="user_verify" 
          name="user_verify" 
          required 
          value={values.user_verify}
          onChange={event => handleChangeDirectly("user_verify", event.target.value)}
          />
        <input type="submit" value="Sign Up" />
      </form>
    </main>
  );

  function handleSubmit(event) {
    event.preventDefault();
    const errMsg = signupValidator(values);
    setErrors(errMsg);
    if(!errMsg.length > 0) {
      const data = new FormData(event.target);
      const url = api + "users.php";
      axios.post(url, data)
      .then(res => {
        console.log(res);
        if (res.data.status == 1) {
          window.sessionStorage.setItem('user', JSON.stringify(res.data.user.data));
          history.push(`/user/${res.data.user.data.user_id}`);
        }
      })
      .catch(err=>{
        console.log(err);
        setErrors(["Failed to add new user. Please try again in a minute."]);
      });
    }
  }

}

export default SignUp;
