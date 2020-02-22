document.addEventListener("DOMContentLoaded", function(event) {
  if(document.getElementById("add-recipe")) {
    initRecipeForm("add-recipe");
  }

  if(document.getElementById("sign-up")) {
    initSignUpForm();
  }
  
});

function initRecipeForm(formID) {
  const form = document.getElementById(formID);

  const allIngredients = document.getElementById("all-ingredients");

  const firstInput = document.querySelector('.name-input');
  
  firstInput.onchange = (event)=>{
    if(event.target.value.length > 0) {
      allIngredients.value += event.target.value + "||";
      const firstAmt = firstInput.nextElementSibling;
      firstAmt.onchange = (event)=>{allIngredients.value += event.target.value + "//";};
      addFormRow();
    }
  };

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

function initSignUpForm() {
  const validator = {
    validate: () => {
      var fields = document.getElementById("sign-up").querySelectorAll('input');
      validator.valid = true;
      validator.errors = [];
      fields.forEach((field, index) => {
        if(validator.valid === false) {
          return;
        } else if (field.id == "newsletter") {
          field.value.trim().length !== 0 ? validator.valid = false : validator.valid = true;
          validator.errors.push("Could not verify user as human. This could be due to using an auto-complete feature. Please try again without it.");
        } else {
          let name = field.id;
          validator.setValues(name, field.value);
          if(field.value != null && field.value.trim().length != 0) {
            validator.valid = true;
          } else {
            let msg = field.name + " cannot be blank.";
            validator.errors.push(msg);
            validator.valid = false;
          }
        }
      })
      validator.passwordMatch();
      return validator.valid;
    },
    values: {},
    setValues: (key, value) => {
      validator.values = {...validator.values, [key]: value}
    },
    passwordMatch: () => {
      const password = document.getElementById("password");
      const confirm = document.getElementById("password_confirm");
      if(password.value !== confirm.value) {
        console.log(validator.errors);
        validator.errors.push("Passwords do not match.");
        validator.valid = false;
      }
    },
    errors: [],
    valid: true
  };

  form = document.getElementById("sign-up");

  form.onsubmit = function(event) {
    event.preventDefault();
    validator.validate();

    if(validator.validate()) {
      this.submit();
    } else {
      showErrors(validator);
    }
  }

}

function showErrors(validator) {
  if(document.querySelector(".errors")) {
    const errors = document.querySelector(".errors ul");
    let errorsList = '';
    validator.errors.map(err => {
      errorsList += '<li>' + err + '</li>';
    });
    errors.innerHTML += errorsList;
  } else {
    const form = document.querySelector("form");
    const errorNode = document.createElement("div");
    errorNode.setAttribute('class', 'errors');
    errorNode.innerHTML = '<h3>Please fix these errors</h3>';
    let errorList = '<ul>';
    validator.errors.map(err => {
      errorList += '<li>' + err + '</li>';
    });
    errorList += '</ul>';

    errorNode.innerHTML += errorList;
    form.before(errorNode);
  }
  
}
