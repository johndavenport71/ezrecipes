import React from 'react';
import Header from './components/Global/Header';
import Routes from './components/Routes';
import Footer from './components/Global/Footer';

class App extends React.Component {
 
  render() {
    return (
        <div id="app">
          <Header />
          <Routes />
          <Footer />
        </div> 
    );
  }
}

export default App;
