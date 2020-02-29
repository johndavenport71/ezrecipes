import React from 'react';
import Header from './components/Header';
import Routes from './components/Routes';
import { UserContext } from './components/Context';

class App extends React.Component {
  constructor(props) {
    super(props);

    this.toggleLoggedIn = () => {
      this.setState(state => ({
        loggedIn: !state.loggedIn
      }));
    }

    this.state = {
      loggedIn: false,
      toggleLogin: this.toggleLoggedIn
    }

  }

  render() {
    return (
      <UserContext.Provider value={this.state} >
        <div className="App">
          <Header loggedIn={this.state.loggedIn} />
          <Routes />
        </div>  
      </UserContext.Provider>
    );
  }
}

export default App;
