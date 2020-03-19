import React from 'react';
import LoadingLines from '../LoadingLines';

const LoadingSummary = () => {
  return (
    <>
    <div className="loading-summary">
      <LoadingLines />
    </div>
    <div className="loading-image"></div>
    </>
  );
}

export default LoadingSummary;
