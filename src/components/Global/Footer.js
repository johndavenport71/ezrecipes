import React from 'react';

const Footer = () => {
  const session = window.sessionStorage.getItem('user');

  return (
    <div className="footer">
      <div>
        <a href="/categories">View all categories</a>
        <a href={session ? `/user/${session.user_id}` : '/login'}>Account Management</a>
        <a href="add-recipe">Add a recipe</a>
      </div>
      <div>
        For concerns or support, email: <a href="mailto:jonathandavenport@students.abtech.edu">jonathandavenport@students.abtech.edu</a>
        Icons made by <a href="https://www.flaticon.com/authors/monkik" title="monkik" target="_blank" rel="noopener noreferrer">monkik</a> from <a href="https://www.flaticon.com/" title="Flaticon" target="_blank" rel="noreferrer noopener"> www.flaticon.com</a>
      </div>
    </div>
  )
}

export default Footer;
