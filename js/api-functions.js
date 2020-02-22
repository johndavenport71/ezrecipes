const apiUrl = 'http://localhost:8888/ezrecipes/api/';

document.addEventListener("DOMContentLoaded", function(event) {
  if(document.getElementById('single-recipe')) {
    getRecipe();
  }
  
});

function getRecipe() {
  const url = apiUrl + 'recipes.php' + window.location.search;
  fetch(url)
    .then(res => { return res.json() })
    .then(res => console.log(res));
}
