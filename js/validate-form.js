let form = document.getElementById('form');

form.onsubmit = function(event) {
  event.preventDefault();
  
  if(validator.validate()) {
    this.submit();
  }
}


const validator = {
  validate: function() {
    var fields = document.querySelectorAll('input');
    fields.forEach((field, index) => {
      let name = field.id;
      this.values[name] = field.value; 
      field.value != null ? this.valid = true : this.valid = false;
    })
    return this.valid;
  },
  values: {},
  valid: false
};
