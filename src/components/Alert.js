import React from 'react';

const Alert = ({ errors, message, setOpen }) => {
  return (
    <div className={`alert ${errors ? 'errors' : 'message'}`}>
      {errors && 
        <>
        <h3>Please Fix the following errors</h3>
        <button onClick={() => setOpen()}>
          <img src={require('../assets/icons/close.svg')} width="25" height="25" alt="" />
        </button>
        <ul>
          {errors.map((err, i)=><li key={i}>{err}</li>)}
        </ul>
        </>
      }
      {message && 
        <>
        <h3>{message}</h3>
        <button onClick={() => setOpen()}>
          <img src={require('../assets/icons/close.svg')} width="25" height="25" alt="" />
        </button>
        </>
      }
    </div>
  );
}

export default Alert;
