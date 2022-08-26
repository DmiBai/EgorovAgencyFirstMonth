const http = require('http'),
    url = require('url'),
    express = require('express'),
    crypto = require('crypto');

const iv = crypto.randomBytes(16); //генерация вектора инициализации
const key = crypto.scryptSync('secret', 'salt', 32); //генерация ключа

function enc(message){
    let encrypt = crypto
    .createCipheriv('aes-256-cbc', key, iv)
    .update(message, 'utf8', 'utf8');
    return encrypt;
}

function dec(message){
    let decrypt = crypto
        .createDecipheriv('aes-256-cbc', key, iv)
        .update(message, 'utf8', 'utf8');
    return decrypt;
}

let userId = 1;

app = express();
app.use(express.static(__dirname));
app.use('/home', express.static(__dirname));

app.get('/index', function (request, response){
    response.sendFile('index.html' , {root: __dirname});
});

app.post('/get_token', function(request, response){
    let curId = '' + userId++;
    console.log('hi there' + crypto.createCipheriv('aes-256-cbc', key, iv)
        .update(curId, 'utf8', 'utf8'));
    response.json(curId);
});

app.post('/check_token', function(request, response){
    if(request.body) {
        if(request.body.token) {
            // let token = dec('' + request.body.token);
            let token = request.body.token;
            if ((token > 0) && (token <= userId)) {
                response.json(('true'));
            }
        }
    }
    response.json(('false'));
});

app.listen(3000);