CREATE DATABASE IF NOT EXISTS db;
USE db;
CREATE TABLE Player ( 
	PlayerId int AUTO_INCREMENT NOT NULL,
	PlayerName varchar(20),
	FullName varchar(20),
	PlayerPassword varchar(128),
	PRIMARY KEY (PlayerId)
);
CREATE TABLE Item (
	ItemId int AUTO_INCREMENT NOT NULL,
	ItemName varchar(20),
	ItemType varchar(2),
	Min int,
	Max int,
	Stat varchar(3),
	UpgradeId int,
	PRIMARY KEY (ItemId)
);
CREATE TABLE PlayableCharacter (
	CharacterId int AUTO_INCREMENT NOT NULL,
	PlayerId int NOT NULL,
	CharacterName varchar(20),
	StrStat int,
	AgiStat int,
	IntStat int,
	HpStat int,
	Experience int,
	Gold int,
	ArmorId int,
	WeaponId int,
	PRIMARY KEY (CharacterId),
	FOREIGN KEY (PlayerId) REFERENCES Player(PlayerId),
	FOREIGN KEY (ArmorId) REFERENCES Item(ItemId),
	FOREIGN KEY (WeaponId) REFERENCES Item(ItemId)
);
CREATE TABLE Enemy(
	EnemyId int AUTO_INCREMENT NOT NULL,
	EnemyName varchar(20),
	Zone varchar(20),
	Appearance tinyint,
	HpStat int,
	MinAttack int,
	MaxAttack int,
	MinDefense int,
	MaxDefense int,
	Gold int,
	Experience int,
	PRIMARY KEY (EnemyId)
);