export default function setAttributes(elem, attr) {
  for(let key in attr) {
    elem.setAttribute(key, attr[key]);
  }
}