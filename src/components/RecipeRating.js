import React, { useState } from 'react';
import axios from 'axios';
import Alert from './Alert';
import StarRatings from 'react-star-ratings';

const RecipeRating = ({ recipe }) => {
  const [rating, setRating] = useState(recipe.rating);
  const [message, setMessage] = useState(null);
  const [errors, setErrors] = useState([]);
  const api = process.env.REACT_APP_API_PATH;
  const session = JSON.parse(window.sessionStorage.getItem('user'));

  const handleChange = rating => {
    setRating(rating);
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
        <StarRatings 
          rating={rating}
          changeRating={handleChange}
          numberOfStars={5}
          name="recipe-rating"
          starDimension="25px"
          starSpacing="2px"
          starHoverColor="#FFCA3A"
          starRatedColor="#FFCA3A"
        />
        <button type="submit" onClick={handleSubmit} className="button">Rate it!</button>
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
