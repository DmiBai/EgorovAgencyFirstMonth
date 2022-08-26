$(document).ready(function () {
	$(document).on('submit', '#emailForm', function (event) {
		event.preventDefault();

		let fd = new FormData();

		let files = $('#files')[0].files[0];
		fd.append('file', files);

		$.ajax({
			url: '../../app/handlers/getFile.php',
			type: 'POST',
			contentType: false,
			processData: false,
			data: fd,
			success: function (response) {
				console.log(response);
			},
			error: function (response) {
			}
		});
	});
});
