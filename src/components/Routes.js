import React from 'react';
import { Switch, Route, Redirect } from 'react-router-dom';
import Home from './Home';
import SignUp from './User/SignUp';
import Login from './User/Login';
import Recipe from './Recipe';
import AddRecipe from './AddRecipe';
import SearchResults from './Global/SearchResults';
import SingleUser from './User/SingleUser';
import Category from './Category';
import EditRecipe from './EditRecipe';
import EditUser from './User/EditUser';
import Error404 from './Global/Error404';
import CategoryList from './Global/CategoryList';
import ForgotPassword from './User/ForgotPassword';
import ResetPassword from './User/ResetPassword';

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
      <Route path="/search/" exact>
        <Redirect to="/" />
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
      <Route exact path="/categories">
        <CategoryList />
      </Route>
      <Route path="/forgot-password">
        <ForgotPassword />
      </Route>
      <Route path="/reset-password/:selector/:token">
        <ResetPassword />
      </Route>
      <Route>
        <Error404 />
      </Route>
    </Switch>
  );
}
export default Routes;
