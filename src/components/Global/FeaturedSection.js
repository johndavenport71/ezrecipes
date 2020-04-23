import React from 'react';
import axios from 'axios';

class FeaturedSection extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      api: process.env.REACT_APP_API_PATH,
      root: process.env.REACT_APP_ROOT,
      recipes: [],
      current: 0,
      timer: null
    };

    this.next = this.next.bind(this);
  }

  next() {
    let next = this.state.current + 1;
    if(next >= this.state.recipes.length) {
      next = 0;
    }
    this.setState({current: next});
  }

  componentDidMount() {
    const fetchFeatured = (api) => {
      const url = api + 'recipes.php?id[]=59&id[]=224&id[]=494&id[]=245&id[]=309&id[]=497';
      axios.get(url)
      .then(res => {
        console.log(res);
        this.setState({recipes: res.data.recipes});
      })
      .catch(err => console.log(err));
    }
    fetchFeatured(this.state.api);
    this.setState({timer: setInterval(this.next, 10000)});
  }

  componentWillUnmount() {
    clearInterval(this.state.timer);
  }
  
  render() {
    return (
      <section className="featured">
        <div className="featured-slide">
          {this.state.recipes.length > 0 &&
          <>
          <img src={this.state.recipes[this.state.current].img_path} alt={this.state.recipes[this.state.current].title} width="100%" height="auto" />
          <a href={`/recipe/${this.state.recipes[this.state.current].id}`}>
            <h2>
              {this.state.recipes[this.state.current].title}
            </h2>
            <p>
              {this.state.recipes[this.state.current].description}
            </p>
          </a>
          </>
          }
        </div>
      </section>
    );
  }
}

export default FeaturedSection;
