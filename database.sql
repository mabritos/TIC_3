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
	PRIMARY KEY (id),
	FOREIGN KEY (dropId) REFERENCES items(id),
);

INSERT INTO items values ('1', 'Leather Armor', 'A', '0', '4', 'AGI', NULL, NULL, 'leather-armor.svg');
INSERT INTO items values ('2', 'Wooden Sword', 'W', '2', '5', 'STR', NULL, NULL, 'wooden-sword.svg');
INSERT INTO enemies values ('1', 'Dummy', 'Training Grounds', '1', '10', '0', '1', '0', '0', '10', '10', NULL, NULL);
INSERT INTO enemies values ('2', 'Recruit', 'Training Grounds', '2', '20', '2', '5', '2', '6', '20', '30', NULL, NULL);

ALTER TABLE `db`.`enemies`
ADD COLUMN `icon` VARCHAR(45) NULL AFTER `dropChance`;

UPDATE `db`.`enemies` SET `icon` = 'training-dummy.svg' WHERE (`id` = '1');
UPDATE `db`.`enemies` SET `icon` = 'recruit.svg' WHERE (`id` = '2');