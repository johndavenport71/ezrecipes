import React from 'react';
import setAttributes from '../../utils/setAttributes';

const IngredientsInput = () => {

	const appendIngredients = (value) => {
		const ingredients = document.getElementById('all-ingredients');
		ingredients.value += value;
	}

	const handleFirstInput = (event) => {
		if(event.target.value.length > 0) {
			appendIngredients(event.target.value + "||");
			const firstAmt = event.target.nextElementSibling;
			firstAmt.onblur = (event) => {
				if(event.target.value === "") {
					appendIngredients("0 //");
				} else {
					appendIngredients(event.target.value + "//");
				}
			};
			addFormRow();
		}
	}

	const addFormRow = () => {
		const ingrSection = document.getElementById("ingredient-inputs");
		const inputCount = document.querySelectorAll('.name-input').length;
	
		const nameInput = document.createElement('input');
		setAttributes(nameInput, {"id": "ingr_name" + (inputCount + 1), "name": "ingr_name" + (inputCount + 1), "type": "text", "class": "name-input"});
		const amountInput = document.createElement('input');
		setAttributes(amountInput, {"id": "ingr_amt" + (inputCount + 1), "name": "ingr_amt" + (inputCount + 1), "type": "text"});
		
		ingrSection.append(nameInput);
		ingrSection.append(amountInput);
		
		nameInput.onblur = (event)=>{
			if(event.target.value.length > 0) {
				appendIngredients(event.target.value + "||");
				const nextAmt = nameInput.nextElementSibling;
				nextAmt.onblur = (evt) => {
					if(evt.target.value === "") {
						appendIngredients("0 //");
					} else {
						appendIngredients(evt.target.value + "//");
					}
				};
				addFormRow();
			}
		};
		
	}
	
  return (
		<div id="form-ingredients" className="full-width">
			<div id="ingredient-inputs">
				<label htmlFor="ingr_name1">Ingredient</label>
				<label htmlFor="ingr_amt1">Amount</label>
				<input name="ingr_name1" id="ingr_name1" className="name-input" onBlur={handleFirstInput} />
				<input name="ingr_amt1" id="ingr_amt1" />
			</div>
			<input type="hidden" id="all-ingredients" name="all_ingredients" />
		</div>
	);
}

export default IngredientsInput;
