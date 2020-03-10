import React from 'react';
import Header from './components/Header';
import Routes from './components/Routes';

class App extends React.Component {
  constructor(props) {
    super(props);
  }

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
