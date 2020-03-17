import React from 'react';

const Errors = ({ errors, setOpen }) => {
  return (
    <div className="errors">
      <h3>Please Fix the following errors</h3>
      <button onClick={() => setOpen(false)}>
        <img src={require('../assets/icons/close.svg')} width="25" height="25" alt="" />
      </button>
      <ul>
        {errors.map((err, i)=><li key={i}>{err}</li>)}
      </ul>
    </div>
  );
}

export default Errors;
