function log(any) {
  console.log(any);
}

function setAttributes(elem, attr) {
  for(let key in attr) {
    elem.setAttribute(key, attr[key]);
  }
}