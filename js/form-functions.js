
document.addEventListener("DOMContentLoaded", function(event) {
  initForm("form");
});

function initForm(formID) {
  const form = document.getElementById(formID);

  const allIngredients = document.getElementById("all-ingredients");

  const firstInput = document.querySelector('.name-input');
  log(firstInput);
  firstInput.onchange = (event)=>{
    if(event.target.value.length > 0) {
      allIngredients.value += event.target.value + "||";
      const firstAmt = firstInput.nextElementSibling;
      firstAmt.onchange = (event)=>{allIngredients.value += event.target.value + "//";};
      addFormRow();
    }
  };
  
  log(allIngredients);

}

function addFormRow() {
  const ingrSection = document.getElementById("ingredient-inputs");
  const inputCount = document.querySelectorAll('.name-input').length;
  const allIngredients = document.getElementById("all-ingredients");

  const nameInput = document.createElement('input');
  setAttributes(nameInput, {"id": "ingr_name" + (inputCount + 1), "name": "ingr_name" + (inputCount + 1), "type": "text", "class": "name-input"});
  nameInput.onchange = (event)=>{
    if(event.target.value.length > 0) {
      allIngredients.value += event.target.value + "||";
      const nextAmt = nameInput.nextElementSibling;
      nextAmt.onchange = (event)=>{allIngredients.value += event.target.value + "//";};
      addFormRow();
    }
  };

  const amountInput = document.createElement('input');
  setAttributes(amountInput, {"id": "ingr_amt" + (inputCount + 1), "name": "ingr_amt" + (inputCount + 1), "type": "text"});
  
  ingrSection.append(nameInput);
  ingrSection.append(amountInput);
}


// form.onsubmit = function(event) {
//   event.preventDefault();
  
//   if(validator.validate()) {
//     this.submit();
//   }
// }

// const validator = {
//   validate: function() {
//     var fields = document.querySelectorAll('input');
//     fields.forEach((field, index) => {
//       let name = field.id;
//       this.values[name] = field.value; 
//       field.value != null ? this.valid = true : this.valid = false;
//     })
//     return this.valid;
//   },
//   values: {},
//   valid: false
// };
