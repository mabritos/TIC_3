const pool = require('./pool');

function Zone(){}

Zone.prototype = {
    findEnemy: function(zone, callback) {
        let sql = 'SELECT * FROM enemies WHERE zone = ?';

        pool.query(sql, zone, function (err, enemies) {
            if (err) throw err;

            if (enemies.length) {
                callback(Zone.prototype.randomEnemyFromList(enemies));
            } else {
                callback(null);
            }
        });
    },
    randomEnemyFromList: function(enemies) {
        let maxSum = 0;
        for(let enemy of enemies){
            maxSum += enemy.spawn;
        }
        let arrayWProb = [maxSum];
        let j;
        let tracker = 0;
        let randomIndex = Math.floor(Math.random() * maxSum+1);
        for(let enemy of enemies){
            j = enemy.spawn;
            while(tracker < tracker+j) {
                arrayWProb[tracker] = enemy.id;
                tracker++;
            }
        }
        let enemyId = arrayWProb[randomIndex];
        for(let enemy of enemies){
            if (enemyId == enemy.id)
                return enemy;
        }

    }
};
module.exports = Zone;