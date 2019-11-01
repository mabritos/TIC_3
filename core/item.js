const pool = require('./pool');

function Item(){}

Item.prototype = {
    getItem: function(itemId, callback) {
        let sql = 'SELECT * FROM items WHERE id = ?';

        pool.query(sql, itemId, function (err, result) {
            if (err) throw err;

            if (result.length) {
                callback(result[0]);
            } else {
                callback(null);
            }
        });
    }

};
module.exports = Item;