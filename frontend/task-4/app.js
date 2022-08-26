const request = require('request'),
	express = require('express');
const apiUrl = 'https://api.kraken.com/0/public/AssetPairs';

app = express();
app.use(express.static(__dirname));
app.use('/home', express.static(__dirname));

app.get('/index', function (req, res) {
	res.sendFile('index.html' , {root: __dirname});
});

app.get('/get_api', function (req, res){
	request(
		apiUrl,(err, response, body) => {
			if (err) return res.status(500).send({ message: err })

			return res.send(body)
		}
	)
});

app.listen(3000);