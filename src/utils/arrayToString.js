export default function arrayToString(array, delimiter) {
  let string = "";
  array.map(item => string += item + delimiter);
  return string;
}