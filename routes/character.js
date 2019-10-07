const express = require('express');
const User = require('../core/user');
const router = express.Router();

// create an object from the class User in the file core/user.js
const user = new User();

router.use('/attack', (req, res) => {
	//TODO
	res.send.('attack');
});

module.exports = router;