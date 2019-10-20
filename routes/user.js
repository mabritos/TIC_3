const express = require('express');
const User = require('../core/user');
const Character = require('../core/character');
const router = express.Router();

// create an object from the class User in the file core/user.js
const user = new User();

// Post login data
router.post('/login', (req, res, next) => {
    // The data sent from the user are stored in the req.body object.
    // call our login function and it will return the result(the user data).
    user.login(req.body.username, req.body.password, function(result) {
        if(result) {
            // Store the user data in a session.
            req.session.playerId = result.PlayerId;
            req.session.opp = 1;          
            // redirect the user to the home page.
            res.redirect('/game');
        }else {
            // if the login function returns null send this error message back to the user.
            res.send('Username/Password incorrect!');
        }
    })

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
    user.create(userInput, function(lastId) {
        // if the creation of the user goes well we should get an integer (id of the inserted user)
        if(lastId) {
            // Get the user data by it's id. and store it in a session.
            user.find(lastId, function(result) {
                req.session.playerId = result.PlayerId;                
                const character = new Character();
                characterId = character.createCharacter(result, function(lastId) {
                    if(lastId) {
                        // Get the character data by it's id. and store it in a session.
                        character.findCharacter(lastId, function(result) {
                            req.session.characterId = characterId;
                            req.session.opp = 0;
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
    if(req.session.user) {
        // destroy the session and redirect the user to the index page.
        req.session.destroy(function() {
            res.redirect('/');
        });
    }
});

module.exports = router;