import React from 'react';

const SliderArrows = ({ handleClick }) => {
  return (
    <>
    <button
      className="arrow-button arrow-left"
      onClick={()=>{handleClick("left")}}
    >
    <img 
      src={require('../assets/icons/chevron_left.svg')} 
      width="50" 
      height="50" 
      alt="" 
    />
    </button>
    <button
      className="arrow-button arrow-right" 
      onClick={()=>{handleClick("right")}}
    >
      <img 
        src={require('../assets/icons/chevron_right.svg')} 
        width="50" 
        height="50"         
        alt=""        
      />
    </button>
    </>
  );
}

export default SliderArrows;
