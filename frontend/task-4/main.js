document.addEventListener("DOMContentLoaded", function () {
	let collectionCur = [];
	const urlWS = "wss://ws.kraken.com/";
	let socket = new WebSocket(urlWS);
	let pairs = [];

	let subDiv = document.getElementsByClassName("list-current-subscribe__item-pairs")[0];
	let searchForm = document.getElementById("search");
	let searchFormValue = document.getElementById("searchValue");
	let unsubButtonsList = document.getElementsByClassName("unsub-buttons-list")[0];
	let collection = document.getElementsByClassName("collection")[0];

	socket.onopen = function () {
		console.log('Success');
	}

	getAllPairsFromApi();

	socket.onmessage = function(e){
		let message = JSON.parse(e.data);
		if (message["channelID"] !== undefined){
			if(!pairs.includes(message["pair"])){
				pairs.push(message["pair"]);
				parsePairs();
			}
		}
		else if (message["errorMessage"]) {
			alert (message["errorMessage"])
		}
		else if (message[0] !== undefined) {
			console.log(message[1][0]);
			if (pairs.includes(message[message.length - 1]))
			{
				let itemPrice = document.getElementById("itemPrice" + (pairs.indexOf(message[message.length - 1]) + 1));
				itemPrice.innerHTML = '' + message[1][0][0];
			}
		}
		//[353,[["23110.50000","0.00213205","1660816015.988122","b","l",""]],"trade","XBT/EUR"]
		/*
		0 - channel number
		1 - main data
			0 - price
			1 - value probably
			2 - time prbbl
			3 - buy/sell
			4 - limit/market
		2 - subs name
		3 - pair
		 */
	}

	socket.onclose = function(event) {
		if (event.wasClean) {
			alert(`[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`);
		} else {
			// например, сервер убил процесс или сеть недоступна
			// обычно в этом случае event.code 1006
			alert('[close] Соединение прервано');
		}
	};

	socket.onerror = function(error) {
		alert(`[error] ${error.message}`);
	};

	searchForm.addEventListener("submit", function (event){
		event.preventDefault();
		socketPair(searchFormValue.value);
		searchFormValue.value = '';
	});

	function socketPair(pair){
		socket.send(JSON.stringify({
			"event": "subscribe",
			"pair": [
				pair
			],
			"subscription": {
				"name": "trade"
			}
		}));
	}

	function socketUnsubPair(pair){
		socket.send(JSON.stringify({
			"event": "unsubscribe",
			"pair": [ pair ],
			"subscription": {
				"name": "trade"
			}
		}));
	}

	function parsePairs(){
		console.log(pairs);
		subDiv.innerHTML = '<div class="list-current-subscribe__item-pairs">';
		unsubButtonsList.innerHTML = '<div class="unsub-buttons-list">';
		collection.innerHTML = '';

		let number = 1;
		pairs.forEach(item => {
			subDiv.innerHTML += '<p>' + item + '</p>';
			unsubButtonsList.innerHTML += '<button class="list-current-subscribe__item-btn" id="'
											+ number +'"> unsubscribe </button><br><br>';

			collection.innerHTML += '<div class="collection-col">\n' +
				'                    <div class="collection-list">\n' +
				'                        <div class="collection-list__item">\n' +
				'                            <div class="collection-list__item-text">' + item + '</div>\n' +
				'                            <div class="collection-list__item-text" id="itemPrice'+ number++ +'">00000.00</div>\n' +
				'                            <div class="collection-list__item-text">0.00%</div>\n' +
				'                            <div class="collection-list__item-text">0.00</div>\n' +
				'                        </div>\n' +
				'                    </div>\n' +
				'                </div>'
		});


		document.querySelectorAll('.list-current-subscribe__item-btn').forEach(item => {
			item.addEventListener("click", function (e){
				socketUnsubPair(pairs[item.getAttribute('id') - 1]);
				setTimeout(()=> {
					pairs.splice(item.getAttribute('id') - 1, 1);
					parsePairs();
				}, 500);
			});
		});
	}

	function getAllPairsFromApi(){
		axios.get('/get_api').then((res)=>{
			let val = res.data['result'];
			let values = Object.values(val);
			values.forEach(item =>{
				collectionCur.push(item.wsname);
			});
		});
		console.log(collectionCur);
	}

});
