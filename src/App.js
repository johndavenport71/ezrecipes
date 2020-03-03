import React from 'react';
import Header from './components/Header';
import Routes from './components/Routes';

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
        <div className="App">
          <Header loggedIn={this.state.loggedIn} />
          <Routes />
        </div> 
    );
  }
}

export default App;
