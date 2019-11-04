const express = require('express');
const Character = require('../core/character');
const Zone = require('../core/zone');
const Item = require('../core/item');
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
router.post('/getItems', (req, res) =>{
	itemPrototype.getItem(req.body.character.armorId,function(armor){
		itemPrototype.getItem(req.body.character.weaponId, function(weapon){
			res.send({"weapon": weapon, "armor": armor});
		});
	});
});
router.post('/getItem', (req, res) =>{
	itemPrototype.getItem(req.body.itemId, function(item){
		res.send(item);
	});
});

router.post('/attack', (req, res) => {
	characterPrototype.findCharacter(req.session.characterId, function(character){
		zonePrototype.findEnemy(req.body.zone, function(enemy){
			itemPrototype.getItem(character.weaponId, function(weapon){
				itemPrototype.getItem(character.armorId, function(armor){
					res.send(characterPrototype.fight(character, weapon, armor, enemy));
				});
			});
		});
	});
});

router.post('/equip', (req, res) => {
	if(req.body.item.type == 'A')
		characterPrototype.equipArmor(req.body.item.id, req.session.characterId, function(character){
			res.send(character);
		});
	else
		characterPrototype.equipWeapon(req.body.item.id, req.session.characterId, function(character){
			res.send(character);
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