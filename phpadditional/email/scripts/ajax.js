$(document).ready(function () {
	$(document).on('submit', '#emailForm', function (event) {
		event.preventDefault();

		let fd = new FormData();

		let username = $('#username').val();
		let email = $('#email').val();
		let message = $('#message').val();

		let files = $('#files')[0].files[0];

		fd.append('username', username);
		fd.append('email', email);
		fd.append('message', message);
		fd.append('file', files);

		$.ajax({
			url: './php/handlers/sendEmail.php',
			type: 'POST',
			contentType: false,
			processData: false,
			data: fd,
			success: function (response) {
				console.log(response);
				$('#username').val('');
				$('#email').val('');
				$('#message').val('');
			},
			error: function (response) {
			}
		});
	});
});
