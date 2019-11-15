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

router.post('/upgradeWeapon', (req,res) =>{
	characterPrototype.findCharacter(req.session.characterId,function(character){
		itemPrototype.getItem(character.weaponId, function(weapon){
			if(weapon.upgradeCost && weapon.upgradeId){
				if(character.gold >= weapon.upgradeCost){
					characterPrototype.equipWeapon(weapon.upgradeId, character.id,function(result){
						itemPrototype.getItem(weapon.upgradeId, function(upgradedWeapon){
							characterPrototype.updateGold(character,weapon.upgradeCost, function(result1){
								res.send({'weapon':upgradedWeapon});
							});
						});
					});
				}else
					res.send({'msg': "Not enough Gold"});
			}else
				res.send({'msg': "Weapon not upgradable"});
		});
	});
});
router.post('/upgradeArmor', (req,res) =>{
	characterPrototype.findCharacter(req.session.characterId,function(character){
		itemPrototype.getItem(character.armorId, function(armor){
			if(armor.upgradeCost && armor.upgradeId){
				if(character.gold >= armor.upgradeCost){
					characterPrototype.equipArmor(armor.upgradeId, character.id,function(result){
						itemPrototype.getItem(armor.upgradeId, function(upgradedArmor){
							characterPrototype.updateGold(character,armor.upgradeCost, function(result1){
								res.send({'armor':upgradedArmor});
							});

						});
					});
				}else
					res.send({'msg': "Not enough Gold"});
			}else
				res.send({'msg': "Armor not upgradable"});
		});
	});
});

router.post('/upgradeHp', (req, res) =>{
	characterPrototype.findCharacter(req.session.characterId, function (character) {
		if(character.gold >= 150){
			characterPrototype.updateHp(character, function (nothing) {
				characterPrototype.updateGold(character, 150, function (none) {
					res.send({'msg': "You paid 150 gold to train and gained 5 hp!"})
				});
			});
		}else
			res.send({'msg': "Not enough Gold"});
	});
});

router.post('/upgradeStr', (req, res) =>{
	characterPrototype.findCharacter(req.session.characterId, function (character) {
		if(character.gold >= 150){
			characterPrototype.updateStr(character, function (nothing) {
				characterPrototype.updateGold(character, 150, function (none) {
					res.send({'msg': "You paid 150 gold to train and gained 5 Strength!"})
				});
			});
		}else
			res.send({'msg': "Not enough Gold"});
	});
});

router.post('/upgradeAgi', (req, res) =>{
	characterPrototype.findCharacter(req.session.characterId, function (character) {
		if(character.gold >= 150){
			characterPrototype.updateAgi(character, function (nothing) {
				characterPrototype.updateGold(character, 150, function (none) {
					res.send({'msg': "You paid 150 gold to train and gained 5 Agility!"})
				});
			});
		}else
			res.send({'msg': "Not enough Gold"});
	});
});

router.post('/upgradeInt', (req, res) =>{
	characterPrototype.findCharacter(req.session.characterId, function (character) {
		if(character.gold >= 150){
			characterPrototype.updateInt(character, function (nothing) {
				characterPrototype.updateGold(character, 150, function (none) {
					res.send({'msg': "You paid 150 gold to study and gained 5 Intelligence!"})
				});
			});
		}else
			res.send({'msg': "Not enough Gold"});
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
	itemPrototype.getItem(req.body.itemId, function(item){
		if(item.type == 'A')
			characterPrototype.equipArmor(item.id, req.session.characterId, function(result){
				characterPrototype.findCharacter(req.session.characterId, function(character){
					res.send({"character": character, "item": item});
				});

			});
		else
			characterPrototype.equipWeapon(item.id, req.session.characterId, function(result){
				characterPrototype.findCharacter(req.session.characterId, function(character){
					res.send({"character": character, "item": item});
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