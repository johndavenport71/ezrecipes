export default function passwordCheck(str) {
  const regx = /^.*(?=.{8,})(?=.*[a-zA-Z])(?=.*\d)(?=.*[!#$%&? "]).*$/g;
  return str.match(regx);
}