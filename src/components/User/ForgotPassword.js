import React, { useState } from 'react';
import axios from 'axios';

const ForgotPassword = () => {
  const api = process.env.REACT_APP_API_PATH;
  const [email, setEmail] = useState("");
  const [submitted, setSubmitted] = useState(false);

  const handleChange = evt => {
    setEmail(evt.target.value);
  }

  const handleSubmit = evt => {
    evt.preventDefault();
    setSubmitted(true);
    const url = api + 'reset-request.php';
    let params = new FormData();
    params.append('email', email);
    axios.post(url, params)
    .then(res => console.log(res))
    .catch(err => console.log(err));
  }

  return (
    <main>
      <h1>Forgot Password</h1>
      {!submitted ?
      <form id="forgot-password-form" onSubmit={handleSubmit}>
        <p>Enter your email address to get a code to reset your password</p>
        <label htmlFor="email">Email Address</label>
        <input type="email" value={email} onChange={handleChange} />
        <input type="submit" value="Send Code" />
      </form>
      :
      <div>
        <p>Your password reset request has been sent to: {email}</p>
        <p>If there is an account with the email address you provided you will be sent a link to reset your password.</p>
      </div>
    }
    </main>
  )
}

export default ForgotPassword;
