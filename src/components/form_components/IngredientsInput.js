import React, { useState } from 'react';

const IngredientsInput = ({ values, setValues }) => {
	const [ingredients, setIngredients] = useState({
		name: "",
		amount: ""
	});

	const handleChangeDirectly = (key, value) => {
		setIngredients({...ingredients, [key]: value});
	}	

	const handleClick = (e) => {
		e.preventDefault();
		let newIngredients = values.all_ingredients;
		newIngredients.push({name: ingredients.name, amount: ingredients.amount});
		setValues({...values, all_ingredients: newIngredients});
		setIngredients({ name: "", amount: "" });
		document.getElementById("ingr_name1").focus();
	}

	const handleRemove = (e, value) => {
		e.preventDefault();
		let newIngredients = values.all_ingredients.filter(row => row.name !== value);
		setValues({...values, all_ingredients: newIngredients});
	}

  return (
		<div id="form-ingredients" className="full-width">
			<div id="ingredient-inputs">
				<label htmlFor="ingr_name1">Ingredient</label>
				<label htmlFor="ingr_amt1">Amount</label>
				{values.all_ingredients.map((row, i) => (
					<div className="row" key={i}>
						<input className="ingredient-input" value={row.name} readOnly />
						<input className="amount-input" value={row.amount} readOnly />
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
					<input 
						id="ingr_amt1" 
						name="ingr_amt1" 
						className="amount-input"
						value={ingredients.amount} 
						onChange={e => handleChangeDirectly("amount", e.target.value)} 
					/>
				</div>
				<button onClick={handleClick} className="add-row"><span>Add another ingredient</span><img src={require('../../assets/icons/add.svg')} alt="" /></button>
			</div>
		</div>
	);
}

export default IngredientsInput;
