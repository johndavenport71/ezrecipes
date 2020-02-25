import React from 'react';
import Header from './components/Header';
import Home from './components/Home';
import SignUp from './components/SignUp';
import Login from './components/Login';
import { Switch, Route } from 'react-router-dom';

function App() {
  return (
    <div className="App">
      <Header />

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
      </Switch>
    </div>  
  );
}

export default App;
