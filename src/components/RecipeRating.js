import React, { useState } from 'react';
import axios from 'axios';
import Alert from './Alert';

const RecipeRating = ({recipe}) => {
  const [rating, setRating] = useState(1);
  const [message, setMessage] = useState(null);
  const [errors, setErrors] = useState([]);
  const api = process.env.REACT_APP_API_PATH;
  const session = JSON.parse(window.sessionStorage.getItem('user'));

  const handleChange = event => {
    setRating(event.target.value);
  }

  const handleSubmit = event => {
    event.preventDefault();
    const url = api + 'ratings.php';
    let params = new FormData();
    params.append('rating', rating);
    params.append('recipe_id', recipe.id);
    params.append('user_id', session.user_id);
    axios.post(url, params)
    .then(res => {
      if(res.data.status === 1) {
        setMessage(res.data.status_message);
      } else if(res.data.status === 0) {
        setErrors([res.data.status_message]);
      }
    })
    .catch(err => console.log(err));
  }

  return (
    <form id="rating-form" onSubmit={handleSubmit}>
      <p>Average user rating: {recipe.rating === 0 ? 'No ratings yet' : recipe.rating}</p>
      {session &&
      <>
        <fieldset>
          <legend>Your rating:</legend>
          <label htmlFor="rating-1">1</label>
          <input type="radio" name="rating" id="rating-1" value="1" onChange={handleChange} />
          <label htmlFor="rating-2">2</label>
          <input type="radio" name="rating" id="rating-2" value="2" onChange={handleChange} />
          <label htmlFor="rating-3">3</label>
          <input type="radio" name="rating" id="rating-3" value="3" onChange={handleChange} />
          <label htmlFor="rating-4">4</label>
          <input type="radio" name="rating" id="rating-4" value="4" onChange={handleChange} />
          <label htmlFor="rating-5">5</label>
          <input type="radio" name="rating" id="rating-5" value="5" onChange={handleChange} />
        </fieldset>
        <button type="submit" onClick={handleSubmit}>Rate it!</button>
      </>
      }
      {errors.length > 0 &&
        <Alert errors={errors} setOpen={() => setErrors([])} />
      }
      {message &&
        <Alert message={message} setOpen={() => setMessage(null)} />
      }
    </form>
  )
}

export default RecipeRating;
