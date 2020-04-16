import React, { useState } from 'react';
import axios from 'axios';

const SingleComment = ({ comment, session, updateComments, setMessage, setErrors }) => {
  const api = process.env.REACT_APP_API_PATH;
  const root = process.env.REACT_APP_ROOT;
  const [edit, setEdit] = useState(false);
  const [body, setBody] = useState(comment.comment_body);

  const handleSave = () => {
    const url = api + 'comments.php';
    let params = new FormData();
    params.append('comment_body', body);
    params.append('comment_id', comment.comment_id);

    axios.put(url, params)
    .then(res => { 
      if(res.data.status === 1) {
        setEdit(false);
        setMessage('Comment saved');
      } else {
        setErrors(['Failed to update comment']);
      }
    })
    .catch(err => console.log(err));
  }

  const handleDelete = () => {
    const url = api + 'comments.php?comment_id=' + comment.comment_id;
    axios.delete(url)
    .then(res => {
      if(res.data.status === 1) {
        setMessage('Comment deleted');
      } else {
        setErrors(['Failed to delete comment']);
      }
      updateComments();
    })
    .catch(err => console.log(err));
  }

  return (
    <div className="single-comment">
      <div>
        <img src={comment.profile_pic ? root + comment.profile_pic : require('../../assets/icons/happy_face.svg')} width="50" height="50" />
        <h4>
          {comment.display_name ? comment.display_name : comment.first_name + ' ' + comment.last_name}
        </h4>
      </div>
      {session && comment.user_id == session.user_id ?
        <div className="comment-actions">
          {edit ? 
            <button className="secondary-button" onClick={handleSave}>Save</button> 
            : 
            <button className="secondary-button" onClick={() => setEdit(true)}>Edit</button>
          }
          <button className="secondary-button warning" onClick={handleDelete}>Delete</button>
        </div>
        :
        <div className="comment-actions">
          {session && session.member_level === 'a' ? 
            <button className="secondary-button warning" onClick={handleDelete}>
              Delete
            </button>
            :
            <button className="secondary-button warning">
              {
                //todo report feature
              }
              Report
            </button>
          }
        </div>
      }
      {edit ? 
        <textarea className="comment-form" value={body} onChange={e => setBody(e.target.value)} />
        :
        <p>
          {body}
        </p>
      }
      <span>
        {new Date(comment.date_added).toDateString()}
      </span>
    </div>
  )
}

export default SingleComment
