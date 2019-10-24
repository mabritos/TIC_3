const express = require('express');
const Character = require('../core/character');
const router = express.Router();

// create an object from the class User in the file core/user.js
const characterPrototype = new Character();

router.post('/load', (req, res) => {
    if (req.session.userId)
        characterPrototype.findPlayerCharacter(req.session.userId, function(character) {
            req.session.characterId = character.id;
            res.send(character);
        });
    else
        res.send("Your session has expired.");
});

router.post('/attack', (req, res) => {
	let myCharacter;
	let enemies;
	let myWeapon;
	let myArmor;

	characterPrototype.findCharacter(req.session.characterId, function(character){
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