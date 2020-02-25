import React, { useState } from 'react';

const SignUp = () => {
  const [values, setValues] = useState({
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    password_confirm: "",
    newsletter: "",
    user_Verify: ""
  });

  const handleChangeDirectly = (key, value) => {
    setValues({...values, [key]: value});
  }

  return (
    <main>
      <h1>Sign Up</h1>
      
      <form id="sign-up" className="user">
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
          value={values.user_Verify}
          onChange={event => handleChangeDirectly("user_verify", event.target.value)}
        />
        <input type="submit" value="Sign Up" />
      </form>
    </main>
  );
}

export default SignUp;
