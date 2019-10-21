const express = require('express');
const User = require('../core/user');
const Character = require('../core/character');
const router = express.Router();

const userPrototype = new User();

router.post('/login', (req, res, next) => {
    var username = req.body.username;
    var password = req.body.password;
    if (username && password) {
        userPrototype.login(req.body.username, req.body.password, function(user) {
            if(user) {
                req.session.userId = user.id;
                res.redirect('/game');
            } else {
                res.send('Username/Password incorrect');
            }
        })
    } else {
        res.send("Plase enter Username and Password");
    }
});


// Post register data
router.post('/register', (req, res, next) => {
    // prepare an object containing all user inputs.
    let userInput = {
        username: req.body.username,
        password: req.body.password,
        email: req.body.email
    };
    // call create function. to create a new user. if there is no error this function will return it's id.
    userPrototype.create(userInput, function(lastId) {
        // if the creation of the user goes well we should get an integer (id of the inserted user)
        if(lastId) {
            // Get the user data by it's id. and store it in a session.
            userPrototype.find(lastId, function(user) {
                req.session.userId = user.id;                
                const character = new Character();
                characterId = character.createCharacter(user, function(lastId) {
                    if(lastId) {
                        // Get the character data by it's id. and store it in a session.
                        character.findCharacter(lastId, function(character) {
                            req.session.characterId = character.id;
                            res.redirect('/login_reg');    
                        });
                    } else {
                        console.log('Error creating a new character ...');
                    }            
                });
            });
        } else {
            console.log('Error creating a new user ...');
        }
    });

});


// Get loggout page
router.get('/logout', (req, res, next) => {
    // Check if the session is exist
    if(req.session.userId) {
        // destroy the session and redirect the user to the index page.
        req.session.destroy(function() {
            res.redirect('/');
        });
    }
});

module.exports = router;