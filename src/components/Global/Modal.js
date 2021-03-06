import React from 'react';

const Modal = (props) => {
  const { children } = props;
  return (
    <div className="modal" id="modal" role="dialog" {...props}>
      {children}
    </div>
  );
}

export default Modal;
