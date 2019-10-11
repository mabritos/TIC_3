var express = require('express');
const bodyParser = require('body-parser');
const session = require('express-session');
const userRouter = require('./routes/user');
const characterRouter = require('./routes/character');
var app = express();
var serv = require('http').Server(app);

//app.use(express.urlencoded( { extended : false}));
app.use(bodyParser.urlencoded({
  extended: true
}));


app.get('/', function(req, res) {
	res.sendFile(__dirname + '/client/index.html');
});
app.use('/client', express.static(__dirname + '/client'));

app.use(session({
    secret:'tic_3_cookie',
    resave: false,
    saveUninitialized: false,
    cookie: {
        maxAge: 60 * 1000 * 30
    }
}));

app.use('/user', userRouter);

app.use('/character', characterRouter);


app.use((req, res, next) =>  {
    var err = new Error('Page not found');
    err.status = 404;
    next(err);
})

app.use((err, req, res, next) => {
    res.status(err.status || 500);
    res.send(err.message);
});

serv.listen(3000, 'localhost');

module.exports = app;