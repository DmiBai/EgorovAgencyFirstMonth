$(document).ready(function () {
	$(document).on('submit', '#captchaForm', function(event){
		event.preventDefault();
		console.log('hi there');
		console.log($('#captcha').val());
		$.ajax({
			url: './handlers/post.php',
			type: 'POST',
			dataType: 'html',
			data: { captcha: $('#captcha').val() },
			success: function (response) {
				let result = JSON.parse(response);
				console.log(result);
				result = result.result;
				console.log(result);
				if(result === 'success'){
					$('#cap_res').text('YOU ARE NOT ROBOT!!!');
				} else {
					$('#cap_res').text('01001110 01001111 00100000 01000101 01001110 01010100 01010010 01011001');
					// location.reload();
				}
			},
			error: function (response) {
			}
		});
	});

	$(document).on('submit', '#imgForm', function(event){
		console.log();
		let fd = new FormData();
		var files = $('#img')[0].files[0];
		console.log(fd);
		fd.append('file', files);
		$.ajax({
			url: './handlers/watermark.php',
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
	})
});
