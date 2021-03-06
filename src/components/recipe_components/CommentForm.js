import React, { useState } from 'react';
import axios from 'axios';

const CommentForm = (props) => {
  const { recipeID, session, updateComments, setMessage, setErrors } = props;
  const [body, setBody] = useState('');
  const api = process.env.REACT_APP_API_PATH;

  const handleSubmit = () => {
    let params = new FormData();
    params.append('recipe_id', recipeID);
    params.append('user_id', session.user_id);
    params.append('comment_body', body);
    const url = api + 'comments.php';
    axios.post(url, params)
    .then(res => {
      console.log(res);
      if(res.data.status === 1) {
        setMessage('Comment added')
        setBody('');
      } else {
        setErrors(['Failed to add comment']);
      }
      updateComments();
    })
    .catch(err => console.log(err));
  }

  return (
    <>
    {session ?
      <h3><label htmlFor="comment-form">Tell people what you think of this recipe</label></h3>
      :
      <h3><label htmlFor="comment-form">Login to leave a comment</label></h3>
    }
    <textarea id="comment-form" disabled={Boolean(!session)} className="comment-form" value={body} onChange={e => setBody(e.target.value)} name="comment_body" rows={10}/>
    <button className="secondary-button" onClick={handleSubmit} disabled={Boolean(!session)}>Post Comment</button>
    </>
  )
}

export default CommentForm;
