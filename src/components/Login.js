import React, { useState } from 'react';

const Login = () => {
  const [values, setValues] = useState({
    email: "",
    password: "",
  });

  const handleChangeDirectly = (key, value) => {
    setValues({...values, [key]: value});
  }

  return (
    <main>
      <h1>Login</h1>
      <form id="login-form" className="user">
        <label htmlFor="email">Email Address</label>
        <input 
          id="email"
          name="email"
          type="email"
          value={values.email}
          onChange={event => handleChangeDirectly("email", event.target.value)}
        />
        <input 
          id="password"
          name="password"
          type="password"
          value={values.password}
          onChange={event => handleChangeDirectly("password", event.target.value)}
        />
        <input type="submit" value="login" />
      </form>
    </main>
  );

}

export default Login;

