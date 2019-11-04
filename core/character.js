const pool = require('./pool');

function randomIntFromInterval(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function Character() {
}

Character.prototype = {

    createCharacter: function(user, callback) {
        
        let sql = 'INSERT INTO characters (userId, name) VALUES (?, ?)';

        pool.query(sql, [user.id, user.username], function (err, result) {
            if (err) throw err;

            callback(result.insertId);
        });

    },

    // Find the playablecharacter data by characteruserId
    findCharacter: function(characterId, callback) {
        // prepare the sql query
        let sql = 'SELECT * FROM characters WHERE id = ?';

        pool.query(sql, characterId, function (err, result) {
            if (err) throw err;

            if (result.length) {
                callback(result[0]);
            } else {
                callback(null);
            }
        });
    },

    
    // Find the playablecharacter data by userId
    findPlayerCharacter: function(userId, callback) {
        // prepare the sql query
        let sql = 'SELECT * FROM characters WHERE userId = ?';

        pool.query(sql, userId, function (err, character) {
            if (err) throw err;

            if (character.length) {
                callback(character[0]);
            } else {
                callback(null);
            }
        });
    },

    equipWeapon: function(itemId, characterId) {
        let sql = 'UPDATE characters SET weaponId = ? WHERE characterId = ?';

        pool.query(sql, [itemId, characterId], function (err, character) {
            if (err) throw err;

            if (character.length) {
                callback(character[0]);
            } else {
                callback(null);
            }
        });
    },

    equipArmor: function(itemId, characterId) {
        let sql = 'UPDATE characters SET armorId = ? WHERE characterId = ?';

        pool.query(sql, [itemId, characterId], function (err, character) {
            if (err) throw err;

            if (character.length) {
                callback(character[0]);
            } else {
                callback(null);
            }
        });
    },

    minAndMaxStatsItemCharacter: function(item, character){
        let min, max;
        if (item.Stat == "STR") {
            min = parseInt(item.min) + parseInt(character.strength);
            max = parseInt(item.max) + parseInt(character.strength);
        }else if (item.Stat == "AGI") {
            min = parseInt(item.min) + parseInt(character.agility);
            max = parseInt(item.max) + parseInt(character.agility);
        }else {
            min = parseInt(item.min) + parseInt(character.intelligence);
            max = parseInt(item.max) + parseInt(character.intelligence);
        }
        return [min, max];

    },

    fight: function(character, weapon, armor, enemy) {
        //calcular vuserIda del character y del enemigo
        let characterHp = character.hp;
        let enemyHp = enemy.hp;
        let loot;

        //calcular ataque del character
        let minAndMaxAttack = Character.prototype.minAndMaxStatsItemCharacter(weapon,character);
        let attackCharacterMin = parseInt(minAndMaxAttack[0]);
        let attackCharacterMax = parseInt(minAndMaxAttack[1]);

        //calcular defensa del character
        let minAndMaxDefense = Character.prototype.minAndMaxStatsItemCharacter(armor,character);
        let defenseCharacterMin = minAndMaxDefense[0];
        let defenseCharacterMax = minAndMaxDefense[1];

        //se simula la pelea, cada ataque se anade al log
        let gameLog=[] ;
        gameLog[0] = "A " + enemy.name +" has appeared!";
        let playerDeals = 0;
        let enemyDeals = 0;
        let i = 0;

        while (characterHp > 0 && enemyHp > 0) {
            playerDeals = randomIntFromInterval(attackCharacterMin, attackCharacterMax) - randomIntFromInterval(enemy.minDefense, enemy.maxDefense);
            if(playerDeals < 0){
                playerDeals = 0;
            }
            enemyHp = enemyHp - playerDeals;
            i++;
            gameLog[i]= character.name + " attacked with " + weapon.name + ", dealing " + playerDeals + " Damage!";
            if (enemyHp <= 0){
                break;
            }
            enemyDeals = randomIntFromInterval(enemy.minAttack, enemy.maxAttack) - randomIntFromInterval(defenseCharacterMin, defenseCharacterMax);
            if(enemyDeals < 0){
                enemyDeals = 0;
            }
            i++;
            characterHp = characterHp - enemyDeals;
            gameLog[i]= enemy.name + " attacked, dealing " + enemyDeals + " Damage!";
        }
        i++;

        //casos si character gana o pierde la pelea
        if(characterHp > 0){
            gameLog[i]= "You've slained " + enemy.name + "!";
            i++;
            gameLog[i] = "You've gained " + enemy.gold + " gold and " + enemy.xp + " xp!";
            if (randomIntFromInterval(0, 100) <= enemy.dropChance) {
                loot = enemy.dropId;
                gameLog[i] = "You found loot in the remains of " + enemy.name;
            }
            character.gold += enemy.gold;
            character.xp += enemy.xp;
            let sql = 'UPDATE characters SET gold = ?, xp = ? WHERE id = ?';
            pool.query(sql, [character.gold, character.xp, character.id], function (err, result) {
                if (err) throw err;
            });
        }else{
            gameLog[i]= "You were defeated by " + enemy.name;
        }

        return {"character": character, "gameLog": gameLog, "enemy": enemy, "loot": loot};

    }
};

module.exports = Character;