import React from 'react';

const Errors = ({ errors }) => {
  return (
    <div className="errors">
      <h3>Please Fix the following errors</h3>
      <ul>
        {errors.map((err, i)=><li key={i}>{err}</li>)}
      </ul>
    </div>
  );
}

export default Errors;
