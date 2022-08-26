const express = require('express');
const cors = require('cors');
const {createServer} = require('http');
let usersOnline = 0;
app = express();

const httpServer = createServer(app);
let io = require('socket.io')(httpServer, {
    cors: {
        credentials: true
    }
});
httpServer.listen('8080');

io.on('connection', (socket) => {
    console.log('user connected');

    socket.on('log', (socket) => {
        usersOnline++;
        io.emit('setUsersNumber', usersOnline);
    });

    socket.on('unlog', (socket) => {
        usersOnline--;
        io.emit('setUsersNumber', usersOnline);
    });

    socket.on('message', (socket) => {
        console.log(socket);
        console.log();
        let message = {
            name: (users[socket.token])[0],
            color: (users[socket.token])[1],
            text: socket.text,
            image: socket.image,
        }
        console.log(message.image);
        io.emit('addMessage', message);
    })

    socket.on('disconnect', () => {
        if(usersOnline > 0){
            usersOnline--;
        }
        console.log('user disconnected');
    })
});
let users = {};

app.use(express.static(__dirname));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));

app.get('/index', function (req, res){
    res.sendFile('index.html', {root: __dirname});
});

app.post('/reg', function (req, res){
    let username = req.body.username;
    let color = req.body.color;

    res.header('Access-Control-Allow-Origin','*');

    if(users.hasOwnProperty(username)){
        res.json('');
    } else {
        let now = new Date();
        let mil = now.getMilliseconds();
        users[mil] = [username, color];
        res.json(mil);
    }
    console.log(users);
});

app.post('/auth', function (req, res){
    let reqToken = req.body.token;
    res.header('Access-Control-Allow-Origin','*');
    if(users.token !== null){
        res.json((users[reqToken])[0]);
    } else {
        res.json('');
    }
});

app.listen(3000);

