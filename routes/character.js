const express = require('express');
const Character = require('../core/character');
const router = express.Router();

// create an object from the class User in the file core/user.js
const characterPrototype = new Character();

router.post('/load', (req, res) => {
	characterPrototype.findPlayerCharacter(req.session.userId, function(character) {
		res.send(character);
	});
});

router.post('/attack', (req, res) => {
	let myCharacter;
	let enemies;
	let myWeapon;
	let myArmor;

	characterPrototype.findCharacter(req.body.characterId, function(character){
		myCharacter = character;
		characterPrototype.findEnemies(req.body.zone, function(enemyList){
			enemies = enemyList;
			characterPrototype.getItem(myCharacter.weaponId, function(weapon){
				myWeapon = weapon;
				characterPrototype.getItem(myCharacter.armorId, function(armor){
					myArmor = armor;
					res.send(characterPrototype.fight(myCharacter, myWeapon, myArmor, enemies));
				});
			});
		});
	});
});

module.exports = router;