import React, { useState } from 'react';

const IngredientsInput = ({ values, setValues }) => {
	const [ingredients, setIngredients] = useState({
		name: ""
	});

	const handleChangeDirectly = (key, value) => {
		setIngredients({ ...ingredients, [key]: value });
	}	

	const handleClick = (e) => {
		e.preventDefault();
		let newIngredients = values.all_ingredients;
		newIngredients.push({ name: ingredients.name });
		setValues({ ...values, all_ingredients: newIngredients });
		setIngredients({ name: "" });
		document.getElementById("ingr_name1").focus();
	}

	const handleRemove = (e, value) => {
		e.preventDefault();
		let newIngredients = values.all_ingredients.filter(row => row.name !== value);
		setValues({ ...values, all_ingredients: newIngredients });
	}

  return (
		<div id="form-ingredients" className="full-width">
			<div id="ingredient-inputs">
				<label htmlFor="ingr_name1">Ingredients</label>
				{values.all_ingredients.map((row, i) => (
					<div className="row" key={i}>
						<input className="ingredient-input" value={row.name} readOnly />
						<button className="remove-row" onClick={(e) => handleRemove(e, row.name)}><img src={require("../../assets/icons/close.svg")} alt=""/></button>
					</div>
				))}
				<div className="row">
					<input 
						id="ingr_name1" 
						name="ingr_name1" 
						className="ingredient-input"
						value={ingredients.name} 
						onChange={e => handleChangeDirectly("name", e.target.value)} 
					/>
				</div>
				<button onClick={handleClick} className="add-row"><span>Add another ingredient</span><img src={require('../../assets/icons/add.svg')} alt="" /></button>
			</div>
		</div>
	);
}

export default IngredientsInput;
