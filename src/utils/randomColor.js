import { sample } from 'lodash';

const colors = [
  '#8DD169', //green
  '#EA9A1B', //orange
  '#E87554', //peach
  '#2E8ECE', //blue
  '#c0c781', //sand
  '#ab4e68', //rose
  '#97a7b3', //grey
];

export default function getRandomColor() {
  return sample(colors);
}
