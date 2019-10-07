CREATE DATABASE IF NOT EXISTS db;
USE db;
CREATE TABLE User ( 
	UserId int AUTO_INCREMENT NOT NULL,
	UserName varchar(20),
	FullName varchar(20),
	Password varchar(128),
	PRIMARY KEY (UserId)
);
CREATE TABLE Item (
	ItemId int AUTO_INCREMENT NOT NULL,
	Name varchar(20),
	Type varchar(2),
	Min int,
	Max int,
	Stat varchar(3),
	UpgradeId int,
	PRIMARY KEY (ItemId)
);
CREATE TABLE Character (
	CharacterId int AUTO_INCREMENT NOT NULL,
	UserId int NOT NULL,
	Name varchar(20),
	StrStat int,
	AgiStat int,
	IntStat int,
	HpStat int,
	Experience int,
	Gold int,
	ArmorId int,
	WeaponId int,
	PRIMARY KEY (CharacterId),
	FOREIGN KEY (UserId) REFERENCES User(UserId),
	FOREIGN KEY (ArmorId) REFERENCES Item(ItemId),
	FOREIGN KEY (WeaponId) REFERENCES Item(ItemId)
);
CREATE TABLE Enemy(
	EnemyId int AUTO_INCREMENT NOT NULL,
	Name varchar(20),
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