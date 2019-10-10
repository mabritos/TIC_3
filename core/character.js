const pool = require('./pool');

function randomIntFromInterval(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function Character() {
};

Character.prototype = {
    // Find the playablecharacter data by characterid
    findCharacter: function(characterId, callback) {
        // prepare the sql query
        let sql = `SELECT * FROM playablecharacter WHERE CharacterId = ?`;


        pool.query(sql, characterId, function (err, result) {
            if (err) throw err;

            if (result.length) {
                callback(result[0]);
            } else {
                callback(null);
            }
        });
    },
    findEnemies: function(zone, callback) {
        let sql = 'SELECT * FROM enemy WHERE Zone = ?';

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
        let sql = 'SELECT * FROM item WHERE ItemId = ?';

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
            min = parseInt(item.Min) + parseInt(character.StrStat);
            max = parseInt(item.Min) + parseInt(character.StrStat);
        }else if (item.Stat == "AGI") {
            min = parseInt(item.Min) + parseInt(character.AgiStat);
            max = parseInt(item.Min) + parseInt(character.AgiStat);
        }else {
            min = parseInt(item.Min) + parseInt(character.IntStat);
            max = parseInt(item.Min) + parseInt(character.IntStat);
        }
        return [min, max];

    },

    fight: function(character, weapon, armor, enemies) {
        //se elije un enemigo al azar de la lista
        let enemy = Character.prototype.randomEnemy(enemies);

        //calcular vida del character y del enemigo
        let characterHp = character.HpStat;
        let enemyHp = enemy.HpStat;

        //calcular ataque del character
        let minAndMaxAttack = Character.prototype.minAndMaxStatsItemCharacter(weapon,character);
        let attackCharacterMin = parseInt(minAndMaxAttack[0]);
        let attackCharacterMax = parseInt(minAndMaxAttack[1]);

        //calcular defensa del character
        let minAndMaxDefense = Character.prototype.minAndMaxStatsItemCharacter(armor,character);
        let defenseCharacterMin = minAndMaxDefense[0];
        let defenseCharacterMax = minAndMaxDefense[1];

        //se simula la pelea, cada ataque se anade al log
        let log = "A " + enemy.EnemyName +" has appeared!\n";
        let playerDeals = 0;
        let enemyDeals = 0;

        while (characterHp > 0 && enemyHp > 0) {
            playerDeals = randomIntFromInterval(attackCharacterMin, attackCharacterMax) - randomIntFromInterval(enemy.MinDefense, enemy.MaxDefense);
            if(playerDeals < 0){
                playerDeals = 0;
            }
            enemyHp = enemyHp - playerDeals;
            log = log + character.CharacterName + " attacked with " + weapon.ItemName + ", dealing " + playerDeals + " Damage!\n";
            if (enemyHp <= 0){
                break;
            }
            enemyDeals = randomIntFromInterval(enemy.MinAttack, enemy.MaxAttack) - randomIntFromInterval(defenseCharacterMin, defenseCharacterMax);
            if(enemyDeals < 0){
                enemyDeals = 0;
            }
            characterHp = characterHp - enemyDeals;
            log = log + enemy.EnemyName + " attacked, dealing " + enemyDeals + " Damage!\n";
        }

        //casos si character gana o pierde la pelea
        if(characterHp > 0){
            log = log + "You've slained " + enemy.EnemyName + "\n" + "You've gained " + enemy.Gold + " gold and " + enemy.Experience + " xp!";
            character.Gold = character.Gold + enemy.Gold;
            character.Experience = character.Experience + enemy.Experience;
            let sql = 'UPDATE playablecharacter SET Gold = ? AND Experience = ? WHERE CharacterId = ?';
            pool.query(sql, [character.Gold, character.Experience, character.CharacterId], function (err, result) {
                if (err) throw err;
            });
        }else{
            log = log + "You were defeated by " + enemy.EnemyName;
        }

        return {"character": character, "log": log};
    }
}

module.exports = Character;