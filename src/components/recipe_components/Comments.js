import React, { useState, useEffect } from 'react';
import CommentForm from './CommentForm';
import SingleComment from './SingleComment';
import Alert from '../Global/Alert';
import axios from 'axios';

const Comments = (props) => {
  const { session, id } = props;
  const api = process.env.REACT_APP_API_PATH;
  const [comments, setComments] = useState([]);
  const [message, setMessage] = useState(null);
  const [errors, setErrors] = useState([]);

  const fetchComments = () => {
    const url = api + 'comments.php?recipe_id=' + id;
    axios.get(url)
    .then(res => {
      console.log(res)
      if(res.data.status === 1) {
        setComments(res.data.data);
      }
    })
    .catch(err => console.log(err));
  }

  useEffect(()=>{
    fetchComments();
  },[]);

  return (
    <aside className="comments">
      <CommentForm 
        recipeID={id} 
        session={session} 
        updateComments={fetchComments} 
        setComments={setComments} 
        setMessage={setMessage}
        setErrors={setErrors}
      />
      {comments && comments.length > 0 && 
        <>
        {comments.map(comment => (
          <SingleComment 
            key={comment.comment_id} 
            comment={comment} 
            session={session} 
            updateComments={fetchComments} 
            setComments={setComments}
            setMessage={setMessage}
            setErrors={setErrors}
          />
        ))}
        </>
      }
      {message &&
        <Alert message={message} setOpen={() => setMessage(null)} />
      }
      {errors.length > 0 &&
        <Alert errors={errors} setOpen={() => setErrors([])} />
      }
    </aside>
  )
}

export default Comments;
