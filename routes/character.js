const express = require('express');
const Character = require('../core/character');
const router = express.Router();

// create an object from the class User in the file core/user.js
const characterPrototype = new Character();
const zonePrototype = new Zone();
const itemPrototype = new Item();

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
	let myEnemy;
	let myWeapon;
	let myArmor;

	characterPrototype.findCharacter(req.session.characterId, function(character){
		myCharacter = character;
		zonePrototype.findEnemy(req.body.zone, function(enemy){
			myEnemy = enemy;
			itemPrototype.getItem(myCharacter.weaponId, function(weapon){
				myWeapon = weapon;
				itemPrototype.getItem(myCharacter.armorId, function(armor){
					myArmor = armor;
					res.send(characterPrototype.fight(myCharacter, myWeapon, myArmor, myEnemy));
				});
			});
		});
	});
});
/*
router.post('/getItem', (req, res) => {
	let myArmor;
	let myWeapon;
	let myChar;

	characterPrototype.findCharacter(req.body.characterId, function (character) {
		myChar = character;
		characterPrototype.getItem(myChar.weaponId, function (weapon) {
			myWeapon = weapon;
			characterPrototype.getItem(myChar.armorId, function (armor) {
				myArmor = armor;
				res.send({"Armor": myArmor, "Weapon": myWeapon});
			});
		});
	});
});
*/
module.exports = router;