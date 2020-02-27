import React from 'react';
import { Switch, Route } from 'react-router-dom';
import Home from './Home';
import SignUp from './SignUp';
import Login from './Login';
import Recipe from './Recipe';
import AddRecipe from './AddRecipe';
import SearchResults from './SearchResults';

const Routes = () => {

  return (
    <Switch>
      <Route exact path="/">
        <Home />
      </Route>
      <Route path="/sign-up">
        <SignUp />
      </Route>
      <Route path="/login">
        <Login />
      </Route>
      <Route path="/recipe/:id">
        <Recipe />
      </Route>
      <Route path="/add-recipe">
        <AddRecipe />
      </Route>
      <Route path="/search/:params">
        <SearchResults />
      </Route>
    </Switch>
  );
}
export default Routes;