const express = require('express');
const Character = require('../core/character');
const router = express.Router();

// create an object from the class User in the file core/user.js
const characterPrototype = new Character();



router.post('/attack', (req, res) => {
	let myCharacter;
	let enemies;
	let myWeapon;
	let myArmor;

	characterPrototype.findCharacter(req.body.CharacterId, function(character){
		myCharacter = character;
		characterPrototype.findEnemies(req.body.Zone, function(enemyList){
			enemies = enemyList;
			characterPrototype.getItem(myCharacter.WeaponId, function(weapon){
				myWeapon = weapon;
				characterPrototype.getItem(myCharacter.ArmorId, function(armor){
					myArmor = armor;
					res.send(characterPrototype.fight(myCharacter, myWeapon, myArmor, enemies));
				});
			});
		});
	});
});

module.exports = router;