import React, { useState } from 'react';

const IngredientsInput = ({ values, setValues }) => {
	const [ingredients, setIngredients] = useState("");

	const handleChangeDirectly = (value) => {
		setIngredients(value);
	}	

	const handleClick = (e) => {
		e.preventDefault();
		let newIngredients = values.ingredients;
		newIngredients.push(ingredients);
		setValues({ ...values, ingredients: newIngredients });
		setIngredients("");
		document.getElementById("ingr_name1").focus();
	}

	const handleRemove = (e, value) => {
		e.preventDefault();
		let newIngredients = values.ingredients.filter(row => row !== value);
		setValues({ ...values, ingredients: newIngredients });
	}

  return (
		<div id="form-ingredients" className="full-width">
			<div id="ingredient-inputs">
				<label htmlFor="ingr_name1">Ingredients</label>
				{values.ingredients && values.ingredients.map((row, i) => (
					<div className="row" key={i}>
						<input className="ingredient-input" value={row} readOnly />
						<button className="remove-row" onClick={(e) => handleRemove(e, row)}><img src={require("../../assets/icons/close.svg")} alt=""/></button>
					</div>
				))}
				<div className="row">
					<input 
						id="ingr_name1" 
						name="ingr_name1" 
						className="ingredient-input"
						value={ingredients} 
						onChange={e => handleChangeDirectly(e.target.value)} 
					/>
				</div>
				<button onClick={handleClick} className="add-row"><span>Add another ingredient</span><img src={require('../../assets/icons/add.svg')} alt="" /></button>
			</div>
		</div>
	);
}

export default IngredientsInput;
