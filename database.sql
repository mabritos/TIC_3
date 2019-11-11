CREATE DATABASE IF NOT EXISTS db;
USE db;
CREATE TABLE users ( 
	id int AUTO_INCREMENT NOT NULL,
	username varchar(50),
	password varchar(128),
	email varchar(100),
	PRIMARY KEY (id)
);
CREATE TABLE items (
	id int AUTO_INCREMENT NOT NULL,
	name varchar(20),
	type varchar(2),
	min int,
	max int,
	stat varchar(3),
	upgradeId int,
	upgradeCost int,
	icon varchar(100),
	PRIMARY KEY (id)
);
CREATE TABLE characters (
	id int AUTO_INCREMENT NOT NULL,
	userId int NOT NULL,
	name varchar(20),
	strength int DEFAULT 10,
	agility int DEFAULT 10,
	intelligence int DEFAULT 10,
	hp int DEFAULT 100,
	xp int DEFAULT 0,
	gold int  DEFAULT 0,
	armorId int  DEFAULT 1,
	weaponId int  DEFAULT 2,
	PRIMARY KEY (id),
	FOREIGN KEY (userId) REFERENCES users(id),
	FOREIGN KEY (armorId) REFERENCES items(id),
	FOREIGN KEY (weaponId) REFERENCES items(id)
);
CREATE TABLE enemies(
	id int AUTO_INCREMENT NOT NULL,
	name varchar(20),
	zone varchar(20),
	spawn tinyint,
	hp int,
	minAttack int,
	maxAttack int,
	minDefense int,
	maxDefense int,
	gold int,
	xp int,
	dropId int,
	dropChance int,
    icon varchar(45) NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (dropId) REFERENCES items(id)
);

INSERT INTO items values ('1', 'Leather Armor', 'A', '0', '4', 'AGI', NULL, NULL, 'leather-armor.svg');
INSERT INTO items values ('2', 'Wooden Sword', 'W', '2', '5', 'STR', NULL, NULL, 'wooden-sword.svg');

INSERT INTO enemies values ('1', 'Dummy', 'Training Grounds', '1', '10', '0', '1', '0', '0', '10', '10', NULL, NULL, 'training-dummy.svg');
INSERT INTO enemies values ('2', 'Recruit', 'Training Grounds', '2', '20', '2', '5', '2', '6', '20', '30', NULL, NULL, 'recruit.svg');

INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('3', 'Wooden Club', 'W', '4', '8', 'STR', '4', '100', 'wood-club-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('4', 'Reinforced Club', 'W', '6', '10', 'STR', '5', '200', 'wood-club-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('5', 'Heavy Club', 'W', '8', '12', 'STR', NULL, NULL, 'wood-club.l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('6', 'Straight Sword', 'W', '10', '14', 'STR', '7', '250', 'two-handed-sword-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('7', 'Long Sword', 'W', '13', '17', 'STR', '8', '500', 'two-handed-sword-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('8', 'Claymore', 'W', '16', '20', 'STR', 'two-handed-sword-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('9', 'Hand Axe', 'W', '20', '21', 'STR', '10', '350', 'tomahawk-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('10', 'Short Axe', 'W', '22', '23', 'STR', '11', '600', 'tomahawk-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('11', 'Tomahawk', 'W', '24', '25', 'STR', 'tomahawk-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('12', 'Spiked Sword', 'W', '24', '30', 'STR', '13', '700', 'shard-sword-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('13', 'Cursed Sword', 'W', '28', '34', 'STR', '14', '1000', 'shard-sword-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('14', 'Crystal Sword', 'W', '32', '38', 'STR', 'shard-sword-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('15', 'Steel Axe', 'W', '30', '40', 'STR', '16', '800', 'battered-axe-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('16', 'Knight Axe', 'W', '35', '45', 'STR', '17', '1200', 'battered-axe-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('17', 'Executioner Axe', 'W', '40', '50', 'STR', 'battered-axe-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('18', 'Broad Sword', 'W', '47', '55', 'STR', '19', '1500', 'pointy-sword-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('19', 'Heavy Sword', 'W', '51', '59', 'STR', '20', '2000', 'pointy-sword-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('20', 'Great Sword', 'W', '55', '63', 'STR', 'pointy-sword-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('21', 'Steel Hammer', 'W', '60', '62', 'STR', '22', '2200', 'thor-hammer-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('22', 'War Hammer', 'W', '68', '70', 'STR', '23', '2800', 'thor-hammer-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('23', 'Mjolnir', 'W', '76', '78', 'STR', 'thor-hammer-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('24', 'Nordic Sword', 'W', '80', '88', 'STR', '25', '3000', 'rune-sword-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('25', 'Enchanted Sword', 'W', '84', '92', 'STR', '26', '4000', 'rune-sword-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('26', 'Runic Blade', 'W', '88', '96', 'STR', 'rune-sword-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('27', 'Great Axe', 'W', '100', '115', 'STR', '28', '3500', 'war-axe-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('28', 'Black Steel Axe', 'W', '110', '125', 'STR', '29', '4800', 'war-axe-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('29', 'Storm Ruler', 'W', '120', '135', 'STR', 'war-axe-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('30', 'Black Knight Sword', 'W', '140', '160', 'STR', '31', '5000', 'broadsword-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('31', 'Royal Blade', 'W', '155', '175', 'STR', '32', '7000', 'broadsword-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('32', 'Durandal', 'W', '170', '185', 'STR', 'broadsword-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('33', 'Champion Halberd', 'W', '180', '200', 'STR', '34', '8500', 'halberd-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('34', 'Lawbringer', 'W', '210', '230', 'STR', '35', '10000', 'halberd-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('35', 'Dragon Slayer', 'W', '240', '260', 'STR', 'halberd-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('36', 'Excalibur', 'W', '300', '350', 'STR', 'excalibur.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('37', 'Small Shield', 'A', '2', '6', 'STR', '38', '100', 'checked-shield-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('38', 'Crest Shield', 'A', '4', '8', 'STR', '39', '200', 'checked-shield-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('39', 'Knight Shield', 'A', '6', '10', 'STR', 'checked-shield-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('40', 'Iron Armor', 'A', '10', '15', 'STR', '41', '350', 'armor-vest-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('41', 'Steel Armor', 'A', '13', '18', 'STR', '42', '500', 'armor-vest-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('42', 'Silver Armor', 'A', '16', '21', 'STR', 'armor-vest-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('43', 'Heavy Shield', 'A', '15', '20', 'STR', '44', '500', 'crenulated-shield-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('44', 'Tower Shield', 'A', '20', '25', 'STR', '45', '750', 'crenulated-shield-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('45', 'Imperial Shield', 'A', '25', '30', 'STR', 'crenulated-shield-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('46', 'Heavy Plate', 'A', '35', '50', 'STR', '47', '1200', 'lamellar-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('47', 'Reinforced Plate', 'A', '45', '60', 'STR', '48', '2000', 'lamellar-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('48', 'Silver Knight Armor', 'A', '55', '70', 'STR', 'lamellar-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('49', 'Imperial Armor', 'A', '80', '100', 'STR', '50', '4500', 'breastplate-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('50', 'Golden Resistance', 'A', '90', '110', 'STR', '51', '6000', 'breastplate-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('51', 'Immovable Force', 'A', '100', '120', 'STR', '52', '10000', 'breastplate-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('52', 'The Lord Protection', 'A', '150', '200', 'STR', 'breastplate-m.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('53', 'Slingshot', 'W', '4', '8', 'AGI', '54', '100', 'slingshot-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('54', 'Metal Slingshot', 'W', '6', '10', 'AGI', '55', '200', 'slingshot-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('55', 'Reinforced Slingshot', 'W', '8', '12', 'AGI', 'slingshot-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('56', 'Wooden Boomerang', 'W', '10', '14', 'AGI', '57', '250', 'boomerang-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('57', 'Strong Boomerang', 'W', '13', '17', 'AGI', '58', '500', 'boomerang-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('58', 'Heavy Boomerang', 'W', '16', '20', 'AGI', 'boomerang-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('59', 'Staff', 'W', '20', '21', 'AGI', '60', '350', 'bo-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('60', 'Heavy Staff', 'W', '22', '23', 'AGI', '61', '600', 'bo-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('61', 'Reinforced Staff', 'W', '24', '25', 'AGI', 'bo-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('62', 'Bow', 'W', '24', '30', 'AGI', '63', '700', 'bow-arrow-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('63', 'Hunting Bow', 'W', '28', '34', 'AGI', '64', '1000', 'bow-arrow-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('64', 'Longbow', 'W', '32', '38', 'AGI', 'bow-arrow-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('65', 'Reinforced Boomerang', 'W', '30', '40', 'AGI', '66', '800', 'armored-boomerang-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('66', 'Iron Boomerang', 'W', '35', '45', 'AGI', '67', '1200', 'armored-boomerang-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('67', 'Steel Boomerang', 'W', '40', '50', 'AGI', 'armored-boomerang-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('68', 'Crossbow', 'W', '47', '55', 'AGI', '69', '1500', 'crossbow-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('69', 'Rapid Fire Crossbow', 'W', '51', '59', 'AGI', '70', '2000', 'crossbow-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('70', 'Knight Crossbow', 'W', '55', '63', 'AGI', 'crossbow-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('71', 'Daggers', 'W', '60', '62', 'AGI', '72', '2200', 'crossed-swords-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('72', 'Silver Swords', 'W', '68', '70', 'AGI', '73', '2800', 'crossed-swords-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('73', 'Assassin Blades', 'W', '76', '78', 'AGI', 'crossed-swords-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('74', 'Composed Bow', 'W', '80', '88', 'AGI', '75', '3000', 'double-shot-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('75', 'Multishot Bow', 'W', '84', '92', 'AGI', '76', '4000', 'double-shot-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('76', 'Sniper Bow', 'W', '88', '96', 'AGI', 'double-shot-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('77', 'Curved Swords', 'W', '100', '115', 'AGI', '78', '3500', 'dervish-swords-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('78', 'Sharp Cimitars', 'W', '110', '125', 'AGI', '79', '4800', 'dervish-swords-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('79', 'Steel Falchions', 'W', '120', '135', 'AGI', 'dervish-swords-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('80', 'Enchanted Bow', 'W', '140', '160', 'AGI', '81', '5000', 'lightning-bow-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('81', 'Sky Piercer', 'W', '155', '175', 'AGI', '82', '7000', 'lightning-bow-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('82', 'Lightning Strike', 'W', '170', '185', 'AGI', 'lightning-bow-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('83', 'Katana', 'W', '180', '200', 'AGI', '84', '8500', 'katana-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('84', 'Uchigatana', 'W', '210', '230', 'AGI', '85', '10000', 'katana-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('85', 'Demon Slayer', 'W', '240', '260', 'AGI', 'katana-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('86', 'Laevatein', 'W', '300', '350', 'AGI', 'laevatein.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('87', 'Leather Shield', 'A', '2', '6', 'AGI', '88', '100', 'round-shield-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('88', 'Parry Shield', 'A', '4', '8', 'AGI', '89', '200', 'round-shield-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('89', 'Buckler', 'A', '6', '10', 'AGI', 'round-shield-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('90', 'Leather Cape', 'A', '10', '15', 'AGI', '91', '350', 'cape-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('91', 'Blackened Cape', 'A', '13', '18', 'AGI', '92', '500', 'cape-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('92', 'Feathers Cape', 'A', '16', '21', 'AGI', 'cape-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('93', 'Dirty Hood', 'A', '15', '20', 'AGI', '94', '500', 'hood-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('94', 'Camouflaged Hood', 'A', '20', '25', 'AGI', '95', '750', 'hood-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('95', 'Spy Hood', 'A', '25', '30', 'AGI', 'hood-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('96', 'Ninja Clothes', 'A', '35', '50', 'AGI', '97', '1200', 'ninja-armor-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('97', 'Shinobi Suit', 'A', '45', '60', 'AGI', '98', '2000', 'ninja-armor-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('98', 'Assassin Armor', 'A', '55', '70', 'AGI', 'ninja-armor-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('99', 'Enchanted Chain', 'A', '80', '100', 'AGI', '100', '4500', 'gem-chain-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('100', 'Evasion Gem', 'A', '90', '110', 'AGI', '101', '6000', 'gem-chain-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('101', 'Thieves Luck', 'A', '100', '120', 'AGI', '102', '10000', 'gem-chain-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('102', 'Wind Favor', 'A', '150', '200', 'AGI', 'gem-chain-m.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('103', 'Mundane Staff', 'W', '4', '8', 'INT', '104', '100', 'broken-staff-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('104', 'Simple Staff', 'W', '6', '10', 'INT', '105', '200', 'broken-staff-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('105', 'Enchanted Staff', 'W', '8', '12', 'INT', 'broken-staff-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('106', 'Basic Wand', 'W', '10', '14', 'INT', '107', '250', 'fairy-wand-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('107', 'Fairy Wand', 'W', '13', '17', 'INT', '108', '500', 'fairy-wand-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('108', 'Fairy King Wand', 'W', '16', '20', 'INT', 'fairy-wand-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('109', 'Fireblast Scroll', 'W', '20', '21', 'INT', '110', '350', 'tied-scroll-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('110', 'Fireball Scroll', 'W', '22', '23', 'INT', '111', '600', 'tied-scroll-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeCost`, `icon`) VALUES ('111', 'Elder Scroll', 'W', '24', '25', 'INT', NULL, 'tied-scroll-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('112', 'Bone Staff', 'W', '24', '30', 'INT', '113', '700', 'bone-mace-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('113', 'Undead Staff', 'W', '28', '34', 'INT', '114', '1000', 'bone-mace-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeCost`, `icon`) VALUES ('114', 'Lich Staff', 'W', '32', '38', 'INT', NULL, 'bone-mace-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('115', 'Mage Staff', 'W', '30', '40', 'INT', '116', '800', 'wizard-staff-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('116', 'Sorcerer Staff', 'W', '35', '45', 'INT', '117', '1200', 'wizard-staff-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('117', 'Witch Staff', 'W', '40', '50', 'INT', 'wizard-staff-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('118', 'Technical Staff', 'W', '47', '55', 'INT', '119', '1500', 'lunar-wand-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('119', 'Mechanical Staff', 'W', '51', '59', 'INT', '120', '2000', 'lunar-wand-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('120', 'High Magic Staff', 'W', '55', '63', 'INT', 'lunar-wand-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('121', 'Crystal Staff', 'W', '60', '62', 'INT', '122', '2200', 'crystal-wand-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('122', 'Ruby Staff', 'W', '68', '70', 'INT', '123', '2800', 'crystal-wand-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('123', 'Diamond Staff', 'W', '76', '78', 'INT', 'crystal-wand-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('124', 'Poison Staff', 'W', '80', '88', 'INT', '125', '3000', 'caduceus-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('125', 'Venom Staff', 'W', '84', '92', 'INT', '126', '4000', 'caduceus-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('126', 'Toxic Staff', 'W', '88', '96', 'INT', 'caduceus-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('127', 'Divination Wand', 'W', '100', '115', 'INT', '128', '3500', 'orb-wand-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('128', 'Alchemy Staff', 'W', '110', '125', 'INT', '129', '4800', 'orb-wand-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('129', 'Malediction Staff', 'W', '120', '135', 'INT', 'orb-wand-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('130', 'Forbidden Tome', 'W', '140', '160', 'INT', '131', '5000', 'secret-book-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('131', 'Book of Secrets', 'W', '155', '175', 'INT', '132', '7000', 'secret-book-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('132', 'Death Evocator', 'W', '170', '185', 'INT', 'secret-book-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('133', 'Wind Scepter', 'W', '180', '200', 'INT', '134', '8500', 'winged-scepter-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('134', 'Gale Scepter', 'W', '210', '230', 'INT', '135', '10000', 'winged-scepter-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('135', 'Storm Scepter', 'W', '240', '260', 'INT', 'winged-scepter-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('136', 'Necronomicon', 'W', '300', '350', 'INT', 'necronomicon.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('137', 'Potion of Resistance', 'A', '2', '6', 'INT', '138', '100', 'drink-me-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('138', 'Potion of Swiftness', 'A', '4', '8', 'INT', '139', '200', 'drink-me-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('139', 'Metal Skin Potion', 'A', '6', '10', 'INT', 'drink-me-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('140', 'Mage Hat', 'A', '10', '15', 'INT', '141', '350', 'pointy-hat-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('141', 'Sorceror Hat', 'A', '13', '18', 'INT', '142', '500', 'pointy-hat-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('142', 'Arch Sage Hat', 'A', '16', '21', 'INT', 'pointy-hat-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('143', 'Robe', 'A', '15', '20', 'INT', '144', '500', 'robe-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('144', 'Wizard Robe', 'A', '20', '25', 'INT', '145', '750', 'robe-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('145', 'Invisibility Cloak', 'A', '25', '30', 'INT', 'robe-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('146', 'Enchanted Ring', 'A', '35', '50', 'INT', '146', '1200', 'skull-signet-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('147', 'Ring of Endurace', 'A', '45', '60', 'INT', '147', '2000', 'skull-signet-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('148', 'Skull Signet', 'A', '55', '70', 'INT', 'skull-signet-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('149', 'Magic Barrier', 'A', '80', '100', 'INT', '149', '4500', 'magic-shield-n.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('150', 'Strong Magic Barrier', 'A', '90', '110', 'INT', '150', '6000', 'magic-shield-vr.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `upgradeId`, `upgradeCost`, `icon`) VALUES ('151', 'Soul Barrier', 'A', '100', '120', 'INT', '151', '10000', 'magic-shield-l.svg');
INSERT INTO `db`.`items` (`id`, `name`, `type`, `min`, `max`, `stat`, `icon`) VALUES ('152', 'God Will', 'A', '150', '200', 'INT', 'magic-shield-m.svg');