import React, { useState } from 'react';
import { useParams, Redirect } from 'react-router-dom';
import passwordCheck from '../../utils/passwordCheck';
import axios from 'axios';
import Modal from '../Global/Modal';
import Alert from '../Global/Alert';

const ResetPassword = () => {
  const { selector, token } = useParams();
  const api = process.env.REACT_APP_API_PATH;
  const [open, setOpen] = useState(false);
  const [message, setMessage] = useState("");
  const [errors, setErrors] = useState([]);
  const [action, setAction] = useState(null);
  const [values, setValues] = useState({
    password: "",
    password_confirm: ""
  });

  const handleChangeDirectly = (key, value) => {
    setValues({...values, [key]: value});
  }

  const handleSubmit = (evt) => {
    evt.preventDefault();
    if(!passwordCheck(values.password)) {
      setErrors(["Your password must contain at least 8 characters, a number and a special character."]);
    } else if(values.password !== values.password_confirm) {
      setErrors(["Passwords do not match."]);
    } else {
      let params = new FormData();
      params.append("password", values.password);
      params.append("password_confirm", values.password_confirm);
      params.append("selector", selector);
      params.append("token", token);
      const url = api + "reset-password.php";
      axios.post(url, params)
      .then(res => {
        console.log(res);
        if(res.data.status === 1) {
          setMessage(res.data.status_message);
          setAction(<a href="/login">Login</a>);
          setOpen(true);
        } else {
          setMessage("Failed to reset password, please try again or request another reset link.");
          setAction(<button className="secondary-button" onClick={() => setOpen(false)}>Close</button>)
          setOpen(true);
        }
      })
      .catch(err => console.log(err));
    }
  }

  return (
    <main>
      {!selector || !token ? 
        <Redirect to="/" />  
        :
        <div>
          <h2>Reset Password</h2>
          <form id="reset-password-form" onSubmit={handleSubmit}>
            <label htmlFor="password">New Password</label>
            <input type="password" name="password" value={values.password} onChange={e => handleChangeDirectly('password', e.target.value)} required />
            <label htmlFor="password">Confirm New Password</label>
            <input type="password" name="password_confirm" value={values.password_confirm} onChange={e => handleChangeDirectly('password_confirm', e.target.value)} required />
            <input type="submit" value="Reset Password" />
          </form>
          {open && 
            <Modal>
              <div>
                <h3>{message}</h3>
                {action}
              </div>
            </Modal>
          }
        </div>
      }
      {
        errors.length > 0 && <Alert errors={errors} setOpen={() => setErrors([])} />
      }
    </main>
  )
}

export default ResetPassword
