let socket = new WebSocket('ws://localhost:4000');

let token = '';
let imageBase64 = '';
document.addEventListener("DOMContentLoaded", function () {

	socket.onopen = function () {

		socket.onmessage = function (event) {
			console.log(event.data);
			let message = JSON.parse(event.data);

			if (message.type === 'login') {
				if (message.data === 'success') {
					$('#join-chat').addClass("d-none");
					$('#exit-chat').removeClass("d-none");
					$('.alert-info').addClass("d-none");
					$('#messForm').removeClass("d-none");
					$('#messDiv').removeClass("d-none");
				} else {
					alert('user already registered and online');
					token = '';
				}
			} else if (message.type === 'message') {
				if (message.data[0] === token) {
					addOwnMessage(message.data[1]);
				} else {
					addMessage(message.data[0], message.data[1]);
				}
			} else if (message.type==='users'){
				console.log(message.data);
			}
		};
	}

	$('#reg').submit(function (event) {
		event.preventDefault();
		console.log(token);
		regMessage();
	});

	$(document).on('click', '#exit-chat', function (event) {
		event.preventDefault();
		$('#exit-chat').addClass("d-none");
		$('#join-chat').removeClass("d-none");
		$('#messForm').addClass("d-none");
		$('.alert-info').removeClass("d-none");
		$('#messDiv').addClass("d-none");

		socket.send(JSON.stringify({type: 'exit', data: token}));

		token = '';
	});

	$(document).on('submit', '#messForm', function () {
		event.preventDefault();
		let messageText = $('#message').val();
		socket.send(JSON.stringify({type: 'message', data: [messageText, token]}));
		$('#message').val('');
	});
});

function regMessage() {
	socket.send(JSON.stringify({
		type: 'auth',
		data: $('#name').val(),
	}));
	token = $('#name').val();
}

function ajaxQueryAuth(url) {
	$.ajax({
		url: url,
		type: 'POST', //метод отправки
		dataType: 'html', //формат данных
		data: {token: token},
		success: function (response) {
			let result = $.parseJSON(response);
			console.log(result);
			$('#nickname').text(result);
			$('#messDiv').removeClass("d-none");
		},
		error: function (response) {
		}
	});
}

function addMessage(name, text) {

	$('#all_mess').append(`<div class='alert alert-success col-6'>
                        <p class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-dark">` + name + `</span>                      
                        <p class="mt-2">` + text + `</p>
                    </div>`);
}

function addOwnMessage(text) {
	$('#all_mess').append(`<div class='alert alert-warning col-6 ml-auto'>
                        <p class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-dark">` + token + `</span>                      
                        <p class="mt-2">` + text + `</p>
                    </div>`);
}