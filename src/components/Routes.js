import React from 'react';
import { Switch, Route } from 'react-router-dom';
import Home from './Home';
import SignUp from './SignUp';
import Login from './Login';
import Recipe from './Recipe';
import AddRecipe from './AddRecipe';
import SearchResults from './SearchResults';
import SingleUser from './SingleUser';
import Category from './Category';
import EditRecipe from './EditRecipe';
import EditUser from './EditUser';

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
      <Route path={["/recipe/:id", "/recipes/:category/recipe/:id"]}>
        <Recipe />
      </Route>
      <Route path="/recipes/:categories" exact>
        <Category />
      </Route>
      <Route path="/add-recipe">
        <AddRecipe />
      </Route>
      <Route path="/edit-recipe/:id">
        <EditRecipe />
      </Route>
      <Route path="/search/:params">
        <SearchResults />
      </Route>
      <Route exact path="/user/:id">
        <SingleUser />
      </Route>
      <Route exact path="/user/edit/:id">
        <EditUser />
      </Route>
    </Switch>
  );
}
export default Routes;
