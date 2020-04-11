import validateEmail from './validateEmail';
import validAnswers from './validAnswers';

export default function signupValidator(values) {
  const errors = [];

  if(values.first_name === "") {
    errors.push("First Name cannot be blank");
  }
  if(values.last_name === "") {
    errors.push("Last Name cannot be blank");
  }
  if(values.email === "") {
    errors.push("Email address cannot be blank");
  } else if (!validateEmail(values.email)) {
    errors.push("Please enter a valid email address");
  }
  if(values.password === "" || values.password_confirm === "") {
    errors.push("You must enter and confirm you password");
  } else if (values.password !== values.password_confirm) {
    errors.push("Passwords do not match.");
  }

  return errors;

}