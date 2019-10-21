const pool = require('./pool');

function randomIntFromInterval(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function Character() {
};

Character.prototype = {

    createCharacter: function(user, callback) {
        
        let sql = 'INSERT INTO characters (userId, name) VALUES (?, ?)';

        pool.query(sql, [user.id, user.username], function (err, result) {
            if (err) throw err;

            callback(result.insertId);
        });

    },

    // Find the playablecharacter data by characteruserId
    findCharacter: function(characteruserId, callback) {
        // prepare the sql query
        let sql = 'SELECT * FROM characters WHERE userId = ?';

        pool.query(sql, characteruserId, function (err, result) {
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

        pool.query(sql, userId, function (err, result) {
            if (err) throw err;

            if (result.length) {
                callback(result[0]);
            } else {
                callback(null);
            }
        });
    },
    
    findEnemy: function(zone, callback) {
        let sql = 'SELECT * FROM enemies WHERE zone = ?';

        pool.query(sql, zone, function (err, result) {
            if (err) throw err;

            if (result.length) {
                callback(result);
            } else {
                callback(null);

            }

        });
    },
    
    getItem: function(itemId, callback) {
        let sql = 'SELECT * FROM items WHERE itemId = ?';

        pool.query(sql, itemId, function (err, result) {
            if (err) throw err;

            if (result.length) {
                callback(result[0]);
            } else {
                callback(null);
            }
        });
    },
    
    randomEnemy: function(enemyList) {
        let appearance = Math.floor(Math.random() * 5);
        let enemy;
        for (let i = 0; i < enemyList.length; i++) {
            if (enemyList[i].Appearance == appearance) {
                return enemy = enemyList[i];
            }
        }
    },
    
    minAndMaxStatsItemCharacter: function(item, character){
        let min, max;
        if (item.Stat == "STR") {
            min = parseInt(item.Min) + parseInt(character.strength);
            max = parseInt(item.Min) + parseInt(character.strength);
        }else if (item.Stat == "AGI") {
            min = parseInt(item.Min) + parseInt(character.agility);
            max = parseInt(item.Min) + parseInt(character.agility);
        }else {
            min = parseInt(item.Min) + parseInt(character.intelligence);
            max = parseInt(item.Min) + parseInt(character.intelligence);
        }
        return [min, max];

    },

    fight: function(character, weapon, armor, enemies) {
        //se elije un enemigo al azar de la lista
        let enemy = Character.prototype.randomEnemy(enemies);

        //calcular vuserIda del character y del enemigo
        let characterHp = character.hp;
        let enemyHp = enemy.hp;

        //calcular ataque del character
        let minAndMaxAttack = Character.prototype.minAndMaxStatsItemCharacter(weapon,character);
        let attackCharacterMin = parseInt(minAndMaxAttack[0]);
        let attackCharacterMax = parseInt(minAndMaxAttack[1]);

        //calcular defensa del character
        let minAndMaxDefense = Character.prototype.minAndMaxStatsItemCharacter(armor,character);
        let defenseCharacterMin = minAndMaxDefense[0];
        let defenseCharacterMax = minAndMaxDefense[1];

        //se simula la pelea, cada ataque se anade al log
        let log = "A " + enemy.name +" has appeared!\n";
        let playerDeals = 0;
        let enemyDeals = 0;

        while (characterHp > 0 && enemyHp > 0) {
            playerDeals = randomIntFromInterval(attackCharacterMin, attackCharacterMax) - randomIntFromInterval(enemy.minDefense, enemy.maxDefense);
            if(playerDeals < 0){
                playerDeals = 0;
            }
            enemyHp = enemyHp - playerDeals;
            log += character.name + " attacked with " + weapon.name + ", dealing " + playerDeals + " Damage!\n";
            if (enemyHp <= 0){
                break;
            }
            enemyDeals = randomIntFromInterval(enemy.minAttack, enemy.maxAttack) - randomIntFromInterval(defenseCharacterMin, defenseCharacterMax);
            if(enemyDeals < 0){
                enemyDeals = 0;
            }
            characterHp = characterHp - enemyDeals;
            log += enemy.name + " attacked, dealing " + enemyDeals + " Damage!\n";
        }

        //casos si character gana o pierde la pelea
        if(characterHp > 0){
            log += "You've slained " + enemy.name + "\n" + "You've gained " + enemy.gold + " gold and " + enemy.xp + " xp!";
            character.gold += enemy.gold;
            character.xp += enemy.xp;
            let sql = 'UPDATE characters SET gold = ?, xp = ? WHERE userId = ?';
            pool.query(sql, [character.gold, character.xp, character.userId], function (err, result) {
                if (err) throw err;
            });
        }else{
            log += "You were defeated by " + enemy.name;
        }

        return {"character": character, "log": log};
    }
}

module.exports = Character;