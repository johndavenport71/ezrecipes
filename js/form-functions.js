
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
      fields.forEach((field, index) => {
        if(validator.valid === false) {
          return;
        }
        let name = field.id;
        validator.setValues(name, field.value);
        field.value != null && field.value.trim().length != 0 ? validator.valid = true : validator.valid = false;
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
        validator.valid = false;
      }
    },
    valid: true
  };

  form = document.getElementById("sign-up");

  form.onsubmit = function(event) {
    event.preventDefault();

    captcha();

    // if(validator.validate()) {
    //   this.submit();
    // }
  }

}

function captcha() {
  if(grecaptcha) {
    const response = grecaptcha.getResponse();
    const url = 'https://www.google.com/recaptcha/api/siteverify?secret=' + GOOGLE_RECAPTCHA_SECRET + '&response=' + response;
    fetch(url, {
      method: 'post',
      mode: 'no-cors'
    }).then(res => {
      console.log(res);
    });
  }
}




