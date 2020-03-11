import React from 'react';
import Header from './components/Header';
import Routes from './components/Routes';

class App extends React.Component {
 
  render() {
    return (
        <div className="App">
          <Header />
          <Routes />
        </div> 
    );
  }
}

export default App;
